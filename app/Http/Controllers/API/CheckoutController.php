<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Address;
use App\Models\Discount;
use Auth;
use Session;
use Cookie;

class CheckoutController extends Controller
{
    public function apply_coupon_code(Request $request)
    {
        
        $coupon = Discount::where('code', $request->code)
                    ->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
                    ->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))
                    ->where('is_active', 1)->first();
        $response_message = array();

        if ($coupon != null) {            
                if (CouponUsage::where('user_id', Auth::user()->id)->where('discount_id', $coupon->id)->count() < $coupon->coupan_uses) {
                    $coupon_details = json_decode($coupon->details);
                    // $carts = Cart::where('user_id', Auth::user()->id)                                    
                    //                 ->update();
                    $carts = Cart::where('user_id', Auth::user()->id)
                                    ->get();
                    $subtotal = 0;
                    $tax = 0;
                    $shipping = 0;
                    $cartITEMS = [];
                    foreach ($carts as $key => $cartItem) {
                        if($coupon->blend_product_discount == 0){// exclude all item has product-discount
                            if(is_null($cartItem['discount_type'])){
                                $cartITEMS[] = $cartItem;
                            }
                        }else if($coupon->blend_product_discount == 1){
                            $cartITEMS[] = $cartItem;
                        }                         
                    }
                    
                    $cItem = [];
                    foreach ($cartITEMS as $key => $cartItem) {
                        if($coupon->blend_with_deals == 0){// exclude all item has deal-discount
                            if(is_null($cartItem['deal_id'])){
                                $cItem[] = $cartItem;
                            }
                        }else if($coupon->blend_with_deals == 1){
                            $cItem[] = $cartItem;
                        }                       
                    }
                    if ($coupon->discount_base == 'product_base') {
                        $cItems = $cItem;
                        $cItem = [];
                        $products = json_decode($coupon->products);
                        foreach($cItems as $item){
                            if(in_array($item->product_id, $products)){
                                $cItem[] = $item;
                            }
                        }
                    }
                    
                    foreach($cItem as $item){
                        if($coupon->discount_type == 'percentage'){
                            $dis = number_format($item['price']*($coupon->discount_amount)/100,2);
                            // dd($coupon->discount_amount);
                        }else{
                            $dis = $item['price'] - $coupon->discount_amount;
                            if($item['price'] < $coupon->discount_amount){
                                $dis = $item['price'];
                            }
                        }
                        Cart::where('id', $item['id'])->update([
                            'coupan_id' => $coupon->id,
                            'coupan_discount_type' => $coupon->discount_type,
                            'coupan_discount_amt' => $coupon->discount_amount,
                            'coupan_discount' => $dis,
                            'coupan_discount_start_date' => $coupon->starts_at,
                            'coupan_discount_end_date' => $coupon->ends_at,
                        ]);
                    }
                    $response_message['status'] = 'success';
                    $response_message['message'] = 'Coupon has been applied';
                } else {
                    $response_message['status'] = 'warning';
                    $response_message['message'] = 'You already used this coupon!';
                }
        } else {
            $response_message['status'] = 'danger';
            $response_message['message'] = 'Invalid coupon!';
        }

        $response_message['items'] = Cart::where('user_id', Auth::user()->id)->get();

        return $response_message;
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
                ->update(
                        [
                            'coupan_discount_type' => null,
                            'coupan_discount_amt' => null,
                            'coupan_discount' => null,
                        ]
        );

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        return ['items' => $carts, 'status' => 'success', 'message' => 'Coupon removed successfully.'];
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash("Please add shipping address")->warning();
            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }

        return view('frontend.delivery_info', compact('carts'));
        // return view('frontend.payment_select', compact('total'));
    }

    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        if($carts->isEmpty()) {
            flash('Your cart is empty')->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = \App\Models\Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                }

                if(isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach(json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (double)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (!$cartItem['shipping_cost'] ||
                            $cartItem['shipping_cost'] == null ||
                            $cartItem['shipping_cost'] == 'null') {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();

            }
            $total = $subtotal + $tax + $shipping;
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));

        } else {
            flash('Your Cart was empty')->warning();
            return redirect()->route('home');
        }
    }
}
