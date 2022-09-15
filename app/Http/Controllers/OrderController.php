<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\Variation;
use App\Models\BusinessLocation;
use App\Models\Business;
use App\Models\ReferenceCount;
use App\Models\Transaction;
use App\Models\TransactionSellLine;
use App\Models\TransactionPayment;
use Auth;
use Session;
use DB;
use Mail;


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

        foreach ($carts as $key => $cart) {
            $subtotal = $subtotal + $cart->quantity*$cart->price;
            $tax = $tax + $cart->tax;
            $discount = $discount + $cart->discount;
            $shipping = $shipping + $cart->shipping_cost;
        }

        $total = $subtotal + $tax - $discount + $shipping;

        $ref_count = $this->setAndGetReferenceCount('draft', 1);
        $invoice_no = $this->generateReferenceNumber('draft', $ref_count, 1);

        $address = Address::where('id', $carts[0]['address_id'])->first();
        $ship = $address->address;
        $shipping_address = $address->name.',; '.$address->address.',; '.',; '.$address->landmark.',; '.$address->city.' - '.$address->postal_code.',; Mob No '.$address->phone;
        $warehouse = $this->getWarehouse($address->postal_code);

        $txn = Transaction::create([
            'business_id' => 1,
            'location_id' => $warehouse['bid'],
            'type' => "sell",
            'status' => "draft",
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
            'payment_status' => 'due',
        ]);
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
                        'unit_price' => $product->price,
                        'line_discount_type' => "fixed",
                        'line_discount_amount' => $product->discount,
                        'unit_price_inc_tax' => $product->price + $product->tax,
                        'item_tax' => $product->tax,
                        'tax_id' => $taxid,
                        'discount_id' => null,
                        'lot_no_line_id' => null,
                        'sell_line_note' => "",
                        'so_line_id' => null,
                        'so_quantity_invoiced' => "0.0000",
                        'res_service_staff_id' => null,
                        'res_line_order_status' => null,
                        'parent_sell_line_id' => null,
                        'children_type' => "",
                        'sub_unit_id' => null,
                    ]);
            }
        }
    }
    
    public function getProduct($id) {
        $product = Product::where('product_custom_field1', $id)->get()->first();
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
