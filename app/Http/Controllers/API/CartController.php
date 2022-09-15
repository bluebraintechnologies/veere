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
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user_id,
                                    'temp_user_id' => null
                                ]
                );

                Session::forget('temp_user_id');
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
            $cart = Cart::where('user_id',  Auth::user()->id);
        }
        $cart_data = $cart->leftJoin('products', 'carts.product_id', '=', 'products.id')
                        ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
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
                                'v.sell_price_inc_tax as product_price')->orderBy('carts.id', 'asc')->get();
        
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
                                    'c1.name as category',
                                    'c1.slug as category_slug',
                                    'products.sku',
                                    'products.image',
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price')->first();
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
}
