<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\User;
use App\Models\Agent_reg;
use App\Models\AgentType;
use App\Models\AgentCommission;
use App\Models\Product;
use App\Models\Variation;
use App\Models\BusinessLocation;
use App\Models\Business;
use App\Models\ReferenceCount;
use App\Models\Transaction;
use App\Models\TransactionSellLine;
use App\Models\TransactionPayment;
use App\Models\CouponUsage;
use Auth;
use Session;
use DB;
use Mail;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
        
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('home');
        }

        $subtotal = 0;
        $total = 0;
        $discount = 0;
        $tax = 0;
        $shipping = 0;
        $coupon_id = null;
        $reward_payment = null;
        $customer_table = "transaction_".Auth::user()->id."c";
        
        
        
        foreach ($carts as $key => $cart) {
            $reward_payment = $cart->pay_reward_points;
            $subtotal = $subtotal + $cart->quantity*$cart->price - $cart->quantity*floatval($cart->coupan_discount);
            $tax = $tax + $cart->tax;
            $discount = $discount + $cart->discount;
            $shipping = $shipping + $cart->shipping_cost;
            if($cart->coupan_id){
                $coupon_id = $cart->coupan_id;
            }
        }
        
        // $total = $subtotal + $tax - $discount + $shipping;
        $total = $subtotal + $tax + $shipping;

        // dd($reward_payment);
        if($reward_payment){
            $total = $total - $reward_payment;
            $ct = DB::table($customer_table)
            ->whereDate('expiry_date', '>=', date('Y-m-d', strtotime('now')))
            ->whereNotNull('remaining')
            ->where('trn_type', 1)
            ->where('status', 1)
            ->get();
            $new_reward_payment = $reward_payment;
            
            if($ct){
                foreach($ct as $c){
                    $c = get_object_vars($c);
                    if($new_reward_payment > 0){
                        if($c['remaining'] > $new_reward_payment){
                            $a =  $c['remaining'] - $new_reward_payment;
                            DB::table($customer_table)->where('id', $c['id'])->update(['remaining' => $a]);
                            $new_reward_payment = 0;
                        }else{
                            $new_reward_payment = $new_reward_payment - $c['remaining'];
                            DB::table($customer_table)->where('id', $c['id'])->update(['remaining' => null]);
                        }
                    }
                }
            }
        }
        //decide how much reward point user will get
        //check wether reward module is enable or  not
        $business = Business::first();
        if($business->enable_rp){
            if($business->cus_rc_value > 0){
                if($business->cus_rc_type == 'flat'){
                    $customer_reward_points = $business->cus_rc_value;
                }else{
                    $customer_reward_points = intval($total*$business->cus_rc_value/100);
                }
            }
        }
        $ref_count = $this->setAndGetReferenceCount('draft', 1);
        $invoice_no = $this->generateReferenceNumber('draft', $ref_count, 1);
        
        $address = Address::where('id', $carts[0]['address_id'])->first();
        $ship = $address->address;
        $shipping_address = $address->name.',; '.$address->address.',; '.',; '.$address->landmark.',; '.$address->city.' - '.$address->postal_code.',; Mob No '.$address->phone;
        $warehouse = $this->getWarehouse($address->postal_code);
        $billing = Address::where('id', $carts[0]['billing_address_id'])->first();
        // $billing_address = $billing->name.',; '.$billing->address.',; '.',; '.$billing->landmark.',; '.$billing->city.' - '.$billing->postal_code.',; Mob No '.$billing->phone.', '.$billing->city.', Pin: '.$billing->postal_code;
        $deliveryTime = $carts[0]['delivery_time'];
        

        $txn = Transaction::create([
            'business_id' => 1,
            'location_id' => $warehouse['bid'],
            'type' => "sell",
            'status' => "final",
            'is_quotation' => "0",
            'contact_id' => Auth::user()->id,
            'invoice_no' => $invoice_no,
            'transaction_date' => date('Y-m-d H:i:s', time()+19800),
            'total_before_tax' => $subtotal,
            'tax_id' => 1,
            'tax_amount' => $tax,
            'discount_type' => ($discount > 0)?'fixed':null,
            'discount_amount' => abs($discount),
            'shipping_status' => "ordered",
            'shipping_charges' => $shipping,
            'final_total' => $total,
            'is_direct_sale' => "1",
            'is_suspend' => "0",
            'exchange_rate' => "1.000",
            'created_by' => "1",
            'custom_field_1' => null,
            'custom_field_2' => null,
            'custom_field_3' => date('Y-m-d H:i:s', time()+19800),
            'custom_field_4' => $warehouse['itype'],
            'shipping_custom_field_1' => null,
            'shipping_custom_field_2' => $address->postal_code,
            'order_addresses' => json_encode($address),
            'is_created_from_api' => "0",
            'pay_term_number' => "7",
            'pay_term_type' => "days",
            'shipping_address' => $address,
            'billing_address' => $billing,
            'delivery_time' => $deliveryTime,
            'payment_status' => 'due',
            'coupon_id' => $coupon_id,
            'reward_payment' => $reward_payment,
        ]);
        CouponUsage::create([
            'discount_id' => $coupon_id,
            'user_id' => Auth::user()->id
        ]);
        $business = Business::first();
        // if ($reward_payment) {
        //     $rp_name = $business->rp_name;
        //             if($business->cus_rc_name){
        //                 $rp_name = $business->cus_rc_name;
        //             }
        //     DB::table($customer_table)->insert([
        //         'order_id' => $txn->id,
        //         'description' => 'you have used '.$reward_payment.' '.$rp_name.' pay for order ',
        //         'amt' => $reward_payment,
        //         'remaining' => $reward_payment,
        //         'expiry_date' => null,
        //         'expiry_date' => date('Y-m-d', strtotime('now')),
        //         'expiry' => null,
        //         'trn_type' => 2,
        //         'status' => 1,
        //     ]);
        // }
        //check for reward point
        $rc_order_max_value = $business->rc_order_max_value;
        if($rc_order_max_value < $total){
            if($business->enable_rp){
                if($business->cus_rc_value > 0){
                    if($business->cus_rc_type == 'flat'){
                        $customer_reward_points = intval($business->cus_rc_value);
                    }else{
                        $customer_reward_points = intval($total*$business->cus_rc_value/100);
                    }
                    if($business->rc_order_redemption && $customer_reward_points > $business->rc_order_redemption){
                        $customer_reward_points = $business->rc_order_redemption;
                    }
                    //customer transaction
                    $rp_name = $business->rp_name;
                    if($business->cus_rc_name){
                        $rp_name = $business->cus_rc_name;
                    }
                    DB::table($customer_table)->insert([
                        'order_id' => $txn->id,
                        'description' => 'you have earned '.$customer_reward_points.' '.$rp_name,
                        'amt' => $customer_reward_points,
                        'remaining' => $customer_reward_points,
                        'expiry_date' => date('Y-m-d', strtotime('now') + 3600*24*$business->rc_expiry),
                        'expiry' => strtotime('now') + 3600*24*$business->rc_expiry,
                        'trn_type' => 1
                    ]); 
                }
                // $user_id = Auth::user()->id;
                // $user = User::find($user_id);
                // $referer_code = $user->referer_code;
                // $agent = Agent_reg::where('referer_code', 'like', $referer_code)->first();
                // if($agent){
                //     if($agent->agent_type_id){
                //         $agent_type = AgentType::find($agent->agent_type_id);
                //         $agent_commission = AgentCommission::where('agent_type_id', $agent->agent_type_id)->orderBy('agent_type_id', 'desc')->first();
                //         if($agent_commission->order_commision_type == 'dicount'){
                //             $agent_reward_points = intval($total*$agent_commission->order_commission/100);
                //         } else{
                //             $agent_reward_points = intval($agent_commission->order_commission);
                //         }

                //         $agent_table = "transaction_".$agent->id."a";
                //         if (Schema::hasTable($agent_table)) {
                //             DB::table($agent_table)->insert([
                //                 'order_id' => $txn->id,
                //                 'description' => 'you have earned '.$agent_reward_points.' '.$business->rp_name,
                //                 'amt' => $agent_reward_points,
                //                 'remaining' => $agent_reward_points,
                //                 'expiry_date' => date('Y-m-d', strtotime('now') + 3600*24*$business->rc_expiry),
                //                 'expiry' => strtotime('now') + 3600*24*$business->rc_expiry,
                //                 'trn_type' => 1
                //             ]);
                //         }
                //     }
                // }

            }
                
        }


        $this->createTransProduct($txn->id, $carts);
        $request->session()->put('combined_order_id', $txn->id);
    }

    
    public function updatePaymentStatus($id, $payment)
    {
        
        $payment = json_decode($payment);
        $order = Transaction::find($id);
        $order->update(['payment_status' => 'paid']);
        TransactionPayment::create([
            'transaction_id' => $order->id,
             'business_id' => $order->business_id,
             'is_return' => 0,
             'amount' => $order->final_total,
             'method' => 'custom_pay_1',
             'transaction_no' => $payment->id,
             'paid_on' => date('Y-m-d', time()),
             'created_by' => 2,
             'is_advance' => 0,
             'payment_for' => $order->contact_id,
             'note' => 'Paid '.$payment->amount.' by '.$payment->method,
             'payment_ref_no' => '',

         ]);
    }

    public function setAndGetReferenceCount($type, $business_id = null)
    {
        if (empty($business_id)) {
            $business_id = 1;
        }

        $ref = ReferenceCount::where('ref_type', $type)
                          ->where('business_id', $business_id)
                          ->first();
        if (!empty($ref)) {
            $ref->ref_count += 1;
            $ref->save();
            return $ref->ref_count;
        } else {
            $new_ref = ReferenceCount::create([
                'ref_type' => $type,
                'business_id' => $business_id,
                'ref_count' => 1
            ]);
            return $new_ref->ref_count;
        }
    }

    /**
     * Generates reference number
     *
     * @param string $type
     * @param int $business_id
     *
     * @return int
     */
    public function generateReferenceNumber($type, $ref_count, $business_id = null, $default_prefix = null)
    {
        $prefix = '';

        if (session()->has('business') && !empty(request()->session()->get('business.ref_no_prefixes')[$type])) {
            $prefix = request()->session()->get('business.ref_no_prefixes')[$type];
        }
        if (!empty($business_id)) {
            $business = Business::find($business_id);
            $prefixes = $business->ref_no_prefixes;
            $prefix = !empty($prefixes[$type]) ? $prefixes[$type] : '';
        }

        if (!empty($default_prefix)) {
            $prefix = $default_prefix;
        }

        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        if (!in_array($type, ['contacts', 'business_location', 'username'])) {
            $ref_year = date('Y');
            $ref_number = $prefix . $ref_year . '/' . $ref_digits;
        } else {
            $ref_number = $prefix . $ref_digits;
        }

        return $ref_number;
    }
    
    public function createTransProduct($tid, $products) 
    {
        if(count($products) >= 1) 
        {
            foreach($products as $product)
            {
                $product_id = $this->getProduct($product->product_id);
                $variation_id = $this->getProductVariation($product_id);
                if($product->tax_percent == 5) { $taxid = 1; }
                elseif($product->tax_percent == 12) { $taxid = 5; }
                else { $taxid = 9; }
                TransactionSellLine::create([
                        'transaction_id' => $tid,
                        'product_id' => $product_id,
                        'variation_id' => $variation_id,
                        'quantity' => $product->quantity,
                        'quantity_returned' => 0,
                        'unit_price_before_discount' => $product->price,
                        'unit_price' => $product->price-$product->coupan_discount,
                        'line_discount_type' => "fixed",
                        'line_discount_amount' => $product->discount + $product->coupan_discount,
                        'unit_price_inc_tax' => $product->price - $product->coupan_discount + $product->tax,
                        'item_tax' => $product->tax,
                        'tax_id' => $taxid,
                        'discount_id' => $product->coupan_id,
                        'lot_no_line_id' => null,
                        'sell_line_note' => "",
                        'so_line_id' => null,
                        'so_quantity_invoiced' => "0.0000",
                        'res_service_staff_id' => null,
                        'res_line_order_status' => null,
                        'parent_sell_line_id' => null,
                        'children_type' => "",
                        'sub_unit_id' => null,
                        'discount_type' => null,
                        'flat_discount' => null,
                        'percent_discount' => null,
                        'discount_start_date' => null,
                        'discount_end_date' => null,
                    ]);
            }
        }
    }
    
    public function getProduct($id) {
        // $product = Product::where('product_custom_field1', $id)->get()->first();
        $product = Product::where('id', $id)->get()->first();
        return $product->id;
    }
    
    public function getProductVariation($id) {
        $var = Variation::where('product_id', $id)->get()->first();
        return $var->id;
    }
    
    public function ifExist($id) {
        return Transaction::where('custom_field_1', $id)->get()->count();
    }
    
    private function getWarehouse($pin) {
        $businesses = BusinessLocation::where('is_active', 1)->get(); 
        foreach($businesses as $business) {
            $ipin = explode(',',$business->inner_pincode);
            $opin = explode(',',$business->outer_pincode);
            if(in_array($pin, $ipin)) {
                return ['bid' => $business->id, 'itype' => 1];
            } elseif(in_array($pin, $opin)) {
                return ['bid' => $business->id, 'itype' => 2];
            }
        }
        return ['bid' => 1, 'itype' => 2];
    }
    
    
}
