<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use Auth;
use Session;
use Cookie;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request){
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            if($request->session()->get('temp_user_id')) {
                $userCartItems = Cart::where('user_id', Auth::user()->id)->get()->pluck('product_id')->toArray(); 
                $userSessionCartItems = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get()->pluck('product_id')->toArray();
                $result = array_intersect($userCartItems, $userSessionCartItems);
                if($result){
                    Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->whereIn('product_id', $result)->delete();
                }
                $cartItems = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user_id,
                                    'temp_user_id' => null
                                ]
                );
                
                Session::forget('temp_user_id');
            }
            $cartItems = Cart::where('user_id', $user_id)->get()->pluck('product_id')->toArray();
            
            foreach($cartItems as $cartItem){
                $pItem = $this->__GetProduct($cartItem);
                $price = $pItem->price;
                if( (strtotime($pItem->discount_start_date) < strtotime('now') ) && (strtotime('now') < (strtotime($pItem->discount_end_date) + 24*60*60-1) ) ){
                    
                    if($pItem->discount_type == 'percent'){
                        $price = intVal($pItem->price*(100-$pItem->percent_discount)*100)/10000;
                    }else{
                        $price = $pItem->price - $pItem->flat_discount;
                    }

                }
                $cItem = Cart::where('user_id', $user_id)->where('product_id', $cartItem)->update([
                    'discount_type' => $pItem['discount_type'],
                    'percent_discount' => $pItem['percent_discount'],
                    'flat_discount' => $pItem['flat_discount'],
                    'discount_start_date' => $pItem['discount_start_date'],
                    'discount_end_date' => $pItem['discount_end_date'],
                    'price' => $price,
                    'oldPrice' => $pItem->price,
                    'discount' => $pItem->price - $price
                ]);
            }
            $carts = $this->__GetCartItem($request, $user_id, 0);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ?  $this->__GetCartItem($request, 0, $temp_user_id) : [] ;
        }
        return ['status' => 'success', 'items' => $carts];        
    }

    public function store(Request $request)
    {
        
        $product = $this->__GetProduct($request->id);
        
        $carts = array();
        $data = array();

        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = Str::random(32);
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        
        $data['product_id'] = $product->id;
        
        $str = '';
        $tax = 0;
        

        $data['quantity'] = 1;
        $data['price'] = $product->price;

        $data['discount_type'] = $product->discount_type;
        $data['percent_discount'] = $product->percent_discount;
        $data['flat_discount'] = $product->flat_discount;
        $data['discount_start_date'] = $product->discount_start_date;
        $data['discount_end_date'] = $product->discount_end_date;
        $data['oldPrice'] = $product->price;
        $oldPrice = $data['price'];
        if( (strtotime($product->discount_start_date) < strtotime('now') ) && (strtotime('now') < (strtotime($product->discount_end_date) + 24*60*60-1) ) ){
            if($product->discount_type == 'percent'){
                $data['price'] = intVal($product->price*(100-$product->percent_discount)*100)/10000;
            }else{
                $data['price'] = $product->price - $product->flat_discount;
            }
        }
        $data['discount'] = $oldPrice - $data['price'];
        if($carts && count($carts) > 0){
            $foundInCart = false;

            foreach ($carts as $key => $cartItem)
            {
                if($cartItem->product_id == $request->id) {
                    $cartItem->quantity += $request->quantity;
                    $cartItem->save();
                    $foundInCart = true;
                }
            }
            if (!$foundInCart) {
                Cart::create($data);
            }
        }
        else{
            Cart::create($data);
        }

        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = $this->__GetCartItem($request, $user_id, 0);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = $this->__GetCartItem($request, 0, $temp_user_id);
        }
            
        return ['status' => 'success', 'items' => $carts];
    }

    public function update($id, Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $cart = Cart::where('user_id', $user_id)->where('id', $id)->first();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = Str::random(32);
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $cart = Cart::where('temp_user_id', $temp_user_id)->where('id', $id)->first();
        }
        
       if($cart ->quantity >= 2) {
        $cart->quantity = $cart->quantity-1;
        $cart->save();
       } else {
        $cart = Cart::where('id', $id)->delete();
       }
        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = $this->__GetCartItem($request, $user_id, 0);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = $this->__GetCartItem($request, 0, $temp_user_id);
        }
            
        return ['status' => 'success', 'items' => $carts];
    }

    public function destroy($id, Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $cart = Cart::where('user_id', $user_id)->where('id', $id)->first();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = Str::random(32);
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $cart = Cart::where('temp_user_id', $temp_user_id)->where('id', $id)->first();
        }
        
       if($cart ->quantity >= 2) {
        $cart->quantity = $cart->quantity-1;
        $cart->save();
       } else {
        $cart = Cart::where('id', $id)->delete();
       }
        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = $this->__GetCartItem($request, $user_id, 0);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = $this->__GetCartItem($request, 0, $temp_user_id);
        }
            
        return ['status' => 'success', 'items' => $carts];
    }

    private function __GetCartItem(Request $request, $uid = null, $tuid = null)
    {
        if($uid == 0) {
            $cart = Cart::where('temp_user_id', $request->session()->get('temp_user_id'));
        } else {
            $cart = Cart::where('user_id',  Auth::user()->id)->select('carts.id', 'carts.product_id');
        }
        
        $cart_data = $cart->leftJoin('products', 'carts.product_id', '=', 'products.id')
                        ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        // ->where('products.featured', 1)
                        // ->where('products.not_for_selling', 0)
                        // ->where('products.is_inactive', 0)
                        ->select('carts.*',
                                'products.new_tag',
                                'products.sale_start',
                                'products.sale_end',
                                'products.name',
                                'products.slug',
                                'c1.name as category',
                                'c1.slug as category_slug',
                                'products.sku',
                                'products.image',
                                'v.sub_sku',
                                // 'v.sell_price_inc_tax as price'
                                )
                                ->orderBy('carts.id', 'asc')
                                ->get();        
        return $cart_data;
    }

    private function __GetProduct($pid = null)
    {
        return Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            ->where('products.id', $pid)
                            ->where('products.is_inactive', 0)
                            ->select('products.id',
                                'products.new_tag',
                                'products.sale_start',
                                'products.sale_end',
                                'products.name',
                                'products.weight',
                                'products.slug',
                                'c1.name as category',
                                'c1.slug as category_slug',
                                'products.sku',
                                'products.image',
                                'products.discount_type',
                                'products.percent_discount',
                                'products.flat_discount',
                                'products.discount_start_date',
                                'products.discount_end_date',
                                'v.sub_sku',
                                'v.sell_price_inc_tax as price', )
                            ->first();
    }

    public function updateAddress(Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            Cart::where('user_id', $user_id)->update(['address_id' => $request->address_id]);
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            Cart::where('user_id', $user_id)->update(['address_id' => $request->address_id]);
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return ['status' => 'success', 'items' => $carts];
    }

    public function updateBillingAddress(Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            Cart::where('user_id', $user_id)->update(['billing_address_id' => $request->address_id]);
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            Cart::where('user_id', $user_id)->update(['billing_address_id' => $request->address_id]);
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return ['status' => 'success', 'items' => $carts];
    }

    public function updateDeliveryTiming(Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            Cart::where('user_id', $user_id)->update(['delivery_time' => $request->timing]);
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            Cart::where('user_id', $user_id)->update(['delivery_time' => $request->timing]);
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return ['status' => 'success', 'items' => $carts];
    }
}
