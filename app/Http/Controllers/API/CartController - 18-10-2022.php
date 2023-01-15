<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Variation;
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
            
            $carts = $this->__GetCartItem($request, $user_id, 0);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ?  $this->__GetCartItem($request, 0, $temp_user_id) : [] ;
        }
        return ['status' => 'success', 'items' => $carts];        
    }
    public function checkStock($product_id){
        $for = 'view_product';
        $filters = [
            "product_id" => $product_id,
            "not_for_selling" => 0,
            "show_manufacturing_data" => 0,
        ];
        $business_id = 1;
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                  ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->whereIn('p.type', ['single']);
                  $products = $query->select(
                    
                    DB::raw("SUM(vld.qty_available) as stock")
                    
                )->groupBy('variations.id', 'vld.location_id');
        return $products->where('p.id', $filters['product_id'])->groupBy('l.id')->get()->toArray();
    }
    public function store(Request $request)
    {
        $product = $this->__GetProduct($request->id);
        
        $carts = array();
        $data = array();

        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            Cart::where('user_id', $user_id)->update(['pay_reward_points' => null]);
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
        
        $data['oldPrice'] = $product->price;
        $oldPrice = $data['price'];

        $netPercentDiscount = 0;
        $netFlatDiscount = 0;
        if( (strtotime($product->discount_start_date) < strtotime('now') ) && (strtotime('now') < (strtotime($product->discount_end_date) + 24*60*60-1) ) ){
            $data['pro_dis'] = 1;
            $data['product_discount_start_date'] = $product->discount_start_date;
            $data['product_discount_end_date'] = $product->discount_end_date;
            if($product->discount_type == 'percent'){
                // $data['price'] = intVal($product->price*(100-$product->percent_discount)*100)/10000;
                $netPercentDiscount = $product->percent_discount + $netPercentDiscount;
            }else{
                // $data['price'] = $product->price - $product->flat_discount;
                $netFlatDiscount = $product->flat_discount + $netFlatDiscount;
            }
        }
        //deals
        $deals_discount_type = null;
        $deals_discount_amount = null;
        $deals_blend_product_discount = null; 
        
        
        if($request->dealsProduct){
            $dealsProduct = $request->dealsProduct;
            if(in_array($product->id, $dealsProduct)){
                $k = array_search ($product->id, $dealsProduct);
                $dealsProductDiscount = $request->dealsProductDiscount;
                // echo '<pre>'; print_r($dealsProductDiscount[$k]); echo '</pre>';
                $deals_discount_type = $dealsProductDiscount[$k]['discount_type'];
                $deals_discount_amount = $dealsProductDiscount[$k]['discount_amount'];
                $deals_blend_product_discount = $dealsProductDiscount[$k]['blend_product_discount'];
                
                $data['deal_dis'] = 1;
                $data['deal_discount_start_date'] = date('Y-m-d', strtotime($dealsProductDiscount[$k]['deal_start_date']));
                $data['dealt_discount_end_date'] = date('Y-m-d', strtotime($dealsProductDiscount[$k]['deal_end_date']));
                if ($deals_blend_product_discount == 1) {
                    if ($deals_discount_type == 'percentage') {
                        $netPercentDiscount = $deals_discount_amount + $netPercentDiscount;
                    }else{
                        $netFlatDiscount = $deals_discount_amount + $netFlatDiscount;
                    }
                }else{
                    if ($deals_discount_type == 'percentage') {
                        $netPercentDiscount = $deals_discount_amount;
                        $netFlatDiscount = 0;
                    }else{
                        $netPercentDiscount = 0;
                        $netFlatDiscount = $deals_discount_amount;
                    }
                    $data['product_discount_start_date'] = null;
                    $data['product_discount_end_date'] = null;
                }
            }
        }
        
        if($netPercentDiscount > 0){
            $data['price'] =  number_format($data['price']*(100 - $netPercentDiscount)/100, 2);
        }
        if($netFlatDiscount){
            $data['price'] = $data['price'] - $netFlatDiscount;
        }
        $data['discount'] = $oldPrice - $data['price'];
        // echo $oldPrice.'-'.$data['price']; die;
        if($carts && count($carts) > 0){
            $foundInCart = false;

            foreach ($carts as $key => $cartItem)
            {
                if($cartItem->product_id == $request->id) {
                    // $stock_detail = array_column($this->checkStock($request->id), 'stock');
                    // $totalStock = 0;
                    // foreach($stock_detail as $stock){
                    //     if($stock){
                    //         $totalStock += intval($stock);
                    //     }
                    // }
                    // if($totalStock < $cartItem->quantity + 1){
                    //     return ['status' => 'no-more-stock'];
                    // }
                    $cartItem->quantity += $request->quantity;
                    $cartItem->save();
                    $foundInCart = true;
                }
            }
            if (!$foundInCart) {
                // $stock_detail = array_column($this->checkStock($request->id), 'stock');
                // $totalStock = 0;
                // foreach($stock_detail as $stock){
                //     if($stock){
                //         $totalStock += intval($stock);
                //     }
                // }
                // if($totalStock == 0){
                //     return ['status' => 'out-of-stock'];
                // }
                Cart::create($data);
            }
        }
        else{
            // $stock_detail = array_column($this->checkStock($request->id), 'stock');
            // $totalStock = 0;
            // foreach($stock_detail as $stock){
            //     if($stock){
            //         $totalStock += intval($stock);
            //     }
            // }
            // if($totalStock == 0){
            //     return ['status' => 'out-of-stock'];
            // }
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
    public function payWithRewardPoints(Request $request){
        Cart::where('user_id', Auth::user()->id)->update(['pay_reward_points' => $request->pay_reward_points]);
        $items = Cart::where('user_id', Auth::user()->id)->get();
        return ['status' => true, 'message' => 'Order amount has been modified', 'items' => $items];
    }
}
