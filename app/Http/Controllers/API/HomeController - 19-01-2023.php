<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agent_reg;
use App\Models\User;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Deals;
use App\Models\Slider;
use App\Models\Discount;
use App\Models\Variation;
use App\Models\Category;
use App\Models\Business;
use App\Models\ProductReview;
use App\Models\ProductLocation;
use App\Models\Promotionalbanner;
use App\Models\BusinessLocation;
use App\Models\Wishlist;
use App\Models\ContactTemp;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\OtherController;
use App\Models\TransactionSellLine;
use App\Models\Address;
class HomeController extends Controller
{
    public function getFeaturedProducts($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id WHERE transactions.status='received' AND transactions.location_id=vld.location_id  AND (pl.variation_id=variations.id)) as stock_price"),
                  
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');

        
        $stockDetail = $products->groupBy('l.id')->get()->toArray(); 
            
        //ids of products
        $locationProducts = array_column($stockDetail, 'product_id');
        
        $categories = Category::where('business_id', 1)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'slug', 'id', 'image'])->orderBy('name', 'asc')->get();
        $featured_category = Product::whereIn('products.id', $locationProducts)
                                    ->where('products.business_id', 1)
                                    ->where('products.type', '!=', 'modifier')
                                    ->where('products.featured', 1)
                                    ->where('products.not_for_selling', 0)
                                    ->where('products.is_inactive', 0)
                                    ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                    ->select(['products.category_id',  'c1.name as category', 'c1.slug'])
                                    ->orderBy('category', 'asc')
                                    ->distinct('category_id')
                                    ->get()
                                    ->toArray();
                                    
        $featured_products = [];
        foreach ($featured_category as $key => $value) {
            $query = Product::whereIn('products.id', $locationProducts)
                        ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.category_id', $value['category_id'])
                        ->where('products.is_inactive', 0);
                        // ->orderBy('products.new_tag', 'desc');
            $fps = $query->select(
                                'products.id',
                                // 'products.new_tag',
                                // 'products.sale_start',
                                // 'products.sale_end',
                                // 'products.name',
                                // 'products.weight',
                                // 'products.slug',
                                // 'c1.name as category',
                                // 'c1.slug as category_slug',
                                // 'products.sku',
                                // 'products.image',
                                // 'products.standard_product_discount_type',
                                // 'products.standard_discount',
                                // 'products.discount_type',
                                // 'products.percent_discount',
                                // 'products.flat_discount',
                                // 'products.discount_start_date',
                                // 'products.discount_end_date',
                                // 'v.sub_sku',
                                // 'v.sell_price_inc_tax as price'
                                )
                            // ->orderBy('products.id', 'asc')   
                            ->orderBy('products.name', 'asc')
                            ->orderBy('products.weight', 'asc')
                                ->get()->toArray();
            $newFps = [];
            $fpsIds = array_column($fps, 'id');
            foreach($stockDetail as $key => $stockvalue){
                if(in_array($stockvalue['product_id'], $fpsIds)){
                    $newFps[] = $stockvalue;
                }
            }            
            array_push($featured_products, ['slug' => $value['slug'], 'products' => $newFps]);
        }
        return ['featured_products' => $featured_products, 'featured_category' => $featured_category];
    }
    public function getBestSellerProductMulLocation($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');
        
        $stockDetail = $products->groupBy('l.id')->get()->toArray(); 
            
        //ids of products
        $locationProducts = array_column($stockDetail, 'product_id');
        $best_sell = TransactionSellLine::selectRaw('COUNT(*) s, product_id')
                        ->groupBy('product_id')
                        ->orderBy('s', 'desc')
                        ->limit(8)
                        ->get()
                        ->toArray();
        $best_sell_pid = array_column($best_sell, 'product_id');
        $newFps = [];
        foreach($stockDetail as $key => $stockvalue){
            if(in_array($stockvalue['product_id'], $best_sell_pid)){
                $newFps[] = $stockvalue;
            }
        }  
        return ['best_sellers' => $newFps];
    }
    public function getBestTopSellingMulLocation($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');
        
        $stockDetail = $products->groupBy('l.id')->get()->toArray(); 
            
        //ids of products
        $locationProducts = array_column($stockDetail, 'product_id');
        $top_sell = TransactionSellLine::selectRaw('SUM(quantity) s, product_id')
                    ->groupBy('product_id')
                    ->orderBy('s', 'desc')
                    ->limit(8)
                    ->get();
        $top_sell_pid = array_column($top_sell->toArray(), 'product_id'); 
            
        $newFps = [];
        foreach($stockDetail as $key => $stockvalue){
            if(in_array($stockvalue['product_id'], $top_sell_pid)){
                $newFps[] = $stockvalue;
            }
        }  
        return ['top_selling' => $newFps];
    }
    public function getBestTopRatedMulLocation($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');
        
        $stockDetail = $products->groupBy('l.id')->get()->toArray(); 
            
        //ids of products
        $locationProducts = array_column($stockDetail, 'product_id');
        $top_rating = ProductReview::selectRaw('AVG(star) rating, product_id')
                        ->groupBy('product_id')
                        ->orderBy('rating', 'desc')
                        ->get()
                        ->toArray();
        $product_rating = array_column($top_rating, 'product_id');
        
        $newFps = [];
        foreach($stockDetail as $key => $stockvalue){
            if(in_array($stockvalue['product_id'], $product_rating)){
                $newFps[] = $stockvalue;
            }
        }  
        return ['top_rated' => $newFps];
    }
    public function getBestNewProductMulLocation($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');
        
        $stockDetail = $products->groupBy('l.id')->get()->toArray(); 
            
        //ids of products
        $locationProducts = array_column($stockDetail, 'product_id');
        $new_arrivals = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.new_tag', 1)
                                ->where('products.is_inactive', 0)
                                ->select('products.id')
                                ->orderBy('products.updated_at', 'desc')
                                ->limit(8)->pluck('id')->toArray();
            
        $newFps = [];
        foreach($stockDetail as $key => $stockvalue){
            if(in_array($stockvalue['product_id'], $new_arrivals)){
                $newFps[] = $stockvalue;
            }
        }  
        return ['new_arrivals' => $newFps];
    }
    public function getDealItems(Request $request){
        $location_id = $request->location;
        $deals = Deals::where('is_active', 1)
            ->where('location_id', $location_id)
            ->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
            ->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))
            ->orderBy('priority', 'desc')
            ->get();
        
        $products = [];
        $toDealProduct = [];
        if($deals){
            foreach($deals as $deal){
                if($deal['products']){
                    $p = json_decode($deal['products']);                    
                    $products = array_unique(array_merge($p, $products));
                }
            }
            sort($products);
            $dealsProduct = [];
            
            foreach($products as $product){             
                $ptest = [];   
                foreach ($deals as $key => $deal) {
                    $p = json_decode($deal['products']);
                    if(in_array($product, $p)){
                        $ptest[$key] = [
                            'id' => $product,
                            'deal_id' => $deal['id'],
                            'priority' => $deal['priority'],
                            'min_qty' => $deal['min_qty'],
                            'standard_product_discount_type' => $deal['standard_product_discount_type'],
                            'standard_discount' => $deal['standard_discount'],
                            'discount_type' => $deal['discount_type'],
                            'discount_amount' => $deal['discount_amount'],
                            'blend_product_discount' => $deal['blend_product_discount'],
                            'blend_with_offer' => $deal['blend_with_offer'],
                            'deal_start_date' => date('Y-m-d', strtotime($deal['starts_at'])),
                            'deal_end_date' => date('Y-m-d', strtotime($deal['ends_at'])),
                            'deal_end_date_timestamp' => date('Y-m-d H:i:s', strtotime($deal['ends_at'])+24*3600-1),
                        ];
                    }
                }
                $toDealProduct[] = array_slice($ptest, 0, 1)[0];
            }
        }
        return ['products' => $products, 'toDealProduct' => $toDealProduct];
    }
    public function getTodayDeal(Request $request){

        $location = $request->location;
        // dd($location);
        $stockDetail = $this->checkStock($request);
        $d = $this->getDealItems($request);
        $dp = $d['products'];
        $dpd = $d['toDealProduct'];
        $rates = ProductReview::selectRaw('AVG(star) rating, product_id')
                    ->whereIn('product_id', $dp)
                    ->groupBy('product_id')
                    ->get()->toArray();
        $proRating = array_combine(array_column($rates, 'product_id'), array_column($rates, 'rating'));
        
        $ac = array_combine(array_column($dpd, 'id'), array_column($dpd, 'deal_end_date_timestamp'));
        $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        // ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
                        ->whereIn('products.id', $dp)
                        ->orderBy('products.new_tag', 'desc');
            $fps = $query->select(
                                'products.id',
                                'products.new_tag',
                                'products.sale_start',
                                'products.sale_end',
                                'products.name',
                                'products.weight',
                                'products.slug',
                                'c1.name as category',
                                'c1.slug as category_slug',
                                'products.standard_product_discount_type',
                                'products.standard_discount',
                                'products.sku',
                                'products.image',
                                'products.standard_product_discount_type',
                                'products.standard_discount',
                                'products.discount_type',
                                'products.percent_discount',
                                'products.flat_discount',
                                'products.discount_start_date',
                                'products.discount_end_date',
                                'v.sub_sku',
                                'v.sell_price_inc_tax as price')->orderBy('products.name', 'asc')->get()->toArray();
            $products = [];
            foreach($fps as $pro){
                $pro['deal_end_date_timestamp'] = $ac[$pro['id']];
                if(isset($stockDetail[$pro['id']])){
                    $pro['enable_stock'] = $stockDetail[$pro['id']]['enable_stock'];
                    $pro['product'] = $stockDetail[$pro['id']]['product'];
                    $pro['product_id'] = $stockDetail[$pro['id']]['product_id'];
                    $pro['stock'] = intval($stockDetail[$pro['id']]['stock']);
                    $pro['stock_price'] = intval($stockDetail[$pro['id']]['stock_price']*100)/100;
                    $pro['unit_price'] = intval($stockDetail[$pro['id']]['unit_price']*100)/100;
                }
                if(isset($proRating[$pro['id']])){
                    $pro['rating'] = number_format($proRating[$pro['id']], 2);
                }else{
                    $pro['rating'] = 0;
                }
                $products[] = $pro;
            }
        // dd($products);
        return ['products' => $products];
    }
    public function getCategoryList(){
        $business_id = 1;
        $category = Category::where('business_id', $business_id)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'description', 'id', 'parent_id'])->orderBy('name', 'asc')->get();

        return $category;
    }

    public function getCategories(){
        return Category::orderBy('name', 'asc')->get();
    } 

    public function getNewProduct(){
        $products = Product::orderBy('created_at', 'asc')->limit(8)->get();
        return $products;
    }
    public function getDealsOfDay(){
        $products = Product::with(['category'])->orderBy('created_at', 'asc')->limit(3)->get();
        return $products;
    }
    public function getMainSliderProducts(){
        $products = [
                    ['link' => 'https://veeere.com', 'image' => '/media/1.jpg'],
                    ['link' => 'https://veeere.com', 'image' => '/media/2.jpg'],
                    ['link' => 'https://veeere.com', 'image' => '/media/3.jpg'],
        ];
        return $products;
    }
    
    public function getSearchProductMulLocation(Request $request){
        $keyword = $request->keyword;
        $location_id = $request->location;
        $business_id = 1;
        $searchTerm = $keyword;
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);
        $searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q->orWhere('p.slug', 'like', "%{$value}%");
                            $q->orWhere('p.name', 'like', "%{$value}%");
                            $q->orWhere('c1.slug', 'like', "%{$value}%");
                            $q->orWhere('c1.name', 'like', "%{$value}%");
                        }
                    })
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');

        
        $stockDetail = $products->groupBy('l.id')->get()->toArray();  
        return ['products' => $stockDetail];
    }
    public function getGroceryListProducts(Request $request){
        $location_id = $request->location;$business_id = 1;
        // getting product details of given location
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)
                  ->where('vld.location_id', $location_id)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.id', 'asc');
        
        $stockDetails = $products->groupBy('l.id')->get()->toArray(); 
        $products = [];
        foreach($stockDetails as $stockDetail){
            $products[$stockDetail['product_id']] = $stockDetail;
        }
        
        $categories = Category::where('business_id', 1)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'slug', 'id'])->orderBy('name', 'asc')->get();
        $featured_category = Product::where('products.business_id', 1)
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
                        ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->select(['products.category_id',  'c1.name as category', 'c1.slug'])
                        ->orderBy('category', 'asc')
                        ->distinct('category_id')
                        ->get()
                        ->toArray();   
        $featured_products = [];                             
        foreach ($featured_category as $key => $value) {
            $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.not_for_selling', 0)
                        ->where('products.category_id', $value['category_id'])
                        ->where('products.is_inactive', 0)
                        //->orderBy('products.new_tag', 'desc')
                        ;
            $fps = $query->select(
                                'products.id')
                            ->orderBy('products.name', 'asc')
                            ->orderBy('products.weight', 'asc')
                            ->get()
                            ->toArray();
            $ids = array_column($fps, 'id');
            $nproducts = [];
            foreach($ids as $id){
                if(isset($products[$id])){
                    $nproducts[] = $products[$id];
                }
            }
            if(count($nproducts) > 0){
                array_push($featured_products, ['category_id' => $value['category_id'], 'slug' => $value['slug'], 'name' => $value['category'], 'products' => $nproducts]);
            }
        }
        $a = $featured_products[0];
        $active_category = $a['slug'];
        return ['featured_products' => $featured_products, 'active_category' => $active_category];
    }
    public function getNewArrivals(){
        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier')
                ->where('category_id', '>', 5);
            $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id');
        $products = $products->orderBy('products.created_at', 'desc')->limit(8)->get()->toArray();
        $data = [];
        $data0 = [];
        $data1 = [];
        $data2 = [];
        $data3 = [];
        foreach($products as $key => $product){
            if($key%2 == 0){
                $data0[] = $product;
            }
            if($key%2 == 1){
                $data1[] = $product;
            } 
        }
        $data = [
            $data0, $data1
        ];
        // echo '<pre>'; print_r($data); echo '</pre>'; die;
        return $data;

        // $products = Product::with(['category'])->orderBy('created_at', 'asc')->inRandomOrder()->limit(8)->get();
        // return $products;
    }
    public function getTopSellings(){
        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier')
                ->where('category_id', '<', 5);
            $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id');
        $products = $products->orderBy('products.created_at', 'desc')->limit(8)->get()->toArray();
        $data = [];
        $data0 = [];
        $data1 = [];
        $data2 = [];
        $data3 = [];
        foreach($products as $key => $product){
            if($key%2 == 0){
                $data0[] = $product;
            }
            if($key%2 == 1){
                $data1[] = $product;
            } 
        }
        $data = [
            $data0, $data1
        ];
        // echo '<pre>'; print_r($data); echo '</pre>'; die;
        return $data;
        // $products = Product::with(['category'])->orderBy('created_at', 'asc')->inRandomOrder()->limit(8)->get();
        // return $products;
    }
    public function getTopRated(){
        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier');
                
            $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id');
        $products = $products->orderBy('products.created_at', 'asc')->limit(8)->get()->toArray();
        $data = [];
        $data0 = [];
        $data1 = [];
        $data2 = [];
        $data3 = [];
        foreach($products as $key => $product){
            if($key%2 == 0){
                $data0[] = $product;
            }
            if($key%2 == 1){
                $data1[] = $product;
            } 
        }
        $data = [
            $data0, $data1
        ];
        // echo '<pre>'; print_r($data); echo '</pre>'; die;
        return $data;
        // $products = Product::with(['category'])->orderBy('created_at', 'asc')->inRandomOrder()->limit(8)->get();
        // return $products;
    }
    public function getAllProducts(){
        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier');
            $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id');
        $allProducts[] = [
            'name' => 'All',
            'slug' => 'all',
            'products' => $products->limit(8)->get()
        ];
        
        $cat = [1,2,3,5];
        $cat = [1,2,3];
        foreach($cat as $category){
            $c = Category::where('id', $category)->first();
            $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier')
                ->where('category_id', '=' ,$category);
            $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id');
                    
            $allProducts[] = [
                'name' => $c->name,
                'slug' => preg_replace('#[ -]+#', '-', trim(preg_replace("/[^A-Za-z0-9 ]/", "", $c->name))),
                'products' => $products->limit(8)->get()
            ];
        }
        return $allProducts;
    }   
    
    public function getAllProductTesting(){

        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier');
        $products = $query->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4'
                    )->groupBy('products.id')->get();
        dd($products);
        echo '<pre>'; print_r($query); echo '</pre>'; die;
    }
    public function getWishlistItems(){
        $wishlists = Wishlist::select('product_id')->where('user_id', Auth::user()->id)->get()->toArray();
        $wishlists = array_column($wishlists, 'product_id');
        
        $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.type', '!=', 'modifier')
                ->whereIn('products.id', $wishlists);
        $products = $query->select(
                    'products.id',
                    'products.new_tag',
                    'products.name',
                    'products.slug',
                    'products.type',
                    'c1.name as category',
                    'c1.slug as category_slug',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'v.sub_sku',
                    'products.image',
                    'products.standard_product_discount_type',
                    'products.standard_discount',
                    'products.discount_type',
                    'products.percent_discount',
                    'products.flat_discount',
                    'products.discount_start_date',
                    'products.discount_end_date',
                    'products.enable_stock',
                    'products.is_inactive',
                    'products.not_for_selling',
                    'products.product_custom_field1',
                    'products.product_custom_field2',
                    'products.product_custom_field3',
                    'products.product_custom_field4',
                    'v.sell_price_inc_tax as price'
                    )
                    ->orderBy('products.name', 'asc')
                    ->get();
                // dd($products);
        return $products;
    }
    public function addProductToWishlist(Request $request){
        $product = $request->json('id');
        if(Wishlist::where('product_id', $product)->count() == 0 ){
            Wishlist::create([
                'user_id' => 1,
                'product_id' => $product
            ]);
        }
        return $this->getWishlistItems();
    }
    public function removeProductFromWishlist(Request $request){
        Wishlist::where('product_id', $request->json('id'))->delete();
        return $this->getWishlistItems();
    }
    public function passwordCheck(Request $request){
        $form = $request->form;
        $user = $request->user;
        $contact = Contact::where('id', $user['id'])->first();
        if($form['password'] == ''){
            return ['status' => 'success'];    
        }
        if($form['password'] && Hash::check($form['password'], $contact->password)){
            return ['status' => 'success'];
        }
        if($form['password'] && !Hash::check($form['password'], $contact->password)){
            return ['status' => 'fail'];
        }
        return ['status' => 'success'];
    }
    public function getSelectedCategoryProduct(Request $request){
        //dd($request->all());
        $location_id = $request->location;
        $business_id = 1;
        $categories = $request->categories;
        $pcategory = Category::whereIn('parent_id', $categories)->get();
        if($pcategory){
            $ncategory = $pcategory->pluck('id')->toArray();
            $categories = array_merge($categories, $ncategory);
        }
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                    ->leftJoin('categories as c1', 'p.category_id', '=', 'c1.id')          
                    ->join('units', 'p.unit_id', '=', 'units.id')
                    ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                    ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                    ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                    ->where('p.business_id', $business_id)
                    ->where('vld.location_id', $location_id)
                    ->where('p.not_for_selling', 0)
                    ->where('p.is_inactive', 0)
                    ->when($categories, function($query,$categories){
                        if(count($categories) > 0){
                            return $query->whereIn('p.category_id',  $categories);
                        }
                    })  
                    ->whereIn('p.type', ['single']);
                    $pl_query_string = $this->get_pl_quantity_sum_string('pl');
        $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id',
                        'p.id',
                        'p.new_tag',
                        'p.sale_start',
                        'p.sale_end',
                        'p.name',
                        'p.weight',
                        'p.slug',
                        'c1.name as category',
                        'c1.slug as category_slug',
                        'p.sku',
                        'p.image',
                        'p.standard_product_discount_type',
                        'p.standard_discount',
                        'p.discount_type',
                        'p.percent_discount',
                        'p.flat_discount',
                        'p.discount_start_date',
                        'p.discount_end_date',
                        'variations.sub_sku',
                        'variations.sell_price_inc_tax as price'
                )->groupBy('variations.id', 'vld.location_id')->orderBy('p.new_tag', 'desc');

        
        $products = $products->groupBy('l.id')->get(); 
        
            $p = array_column($products->toArray(), 'price');
            sort($p);
            $p = $p[count($p)-1];
            $price_range = intval($p/100);

            $wd = array_unique(array_column($products->toArray(), 'weight'));
            sort($wd);
            
            $w = [];
            foreach($wd as $value){
                $w[] = floatval($value);
            }
            $weights = [];
            $weights = $w;
            return ['products' => $products, 'weights' => $weights, 'price_range' => $price_range];
    }
    public function submitProductReview(Request $request){
        $form = $request->form;
        $name = $form['name'];
        $email = $form['email'];
        $content = $form['content'];
        $star = $form['star'];
        $product_id = $form['product_id'];
        if(ProductReview::where('email', 'like', $email)->where('product_id', $request->id)->count() > 0){
            return ['status' => 'exist'];
        }
        ProductReview::create([
            'name' => $name,
            'email' => $email,
            'content' => $content,
            'star' => $star,
            'product_id' => $product_id,
        ]);
        return ['status' => 'success'];
    }
    public function postalCodeDeliveryStatus(Request $request){
        $postal_code = $request->postal_code;
        $id = $request->id;
        $record = ProductLocation::where('product_id', $id)->first();
        if($record){
            
            $business_location = BusinessLocation::where('id', $record->location_id)->where('is_active', 1)->first();
            if($business_location){
                $outer_pincode = explode(",", $business_location->outer_pincode);
                $inner_pincode = explode(",", $business_location->inner_pincode);
                $pincode = array_merge($outer_pincode, $inner_pincode);
                $postal_code = $request->postal_code;
                if(in_array($postal_code, $pincode)){
                    return ['status' => 'success'];
                }else{
                    return ['status' => 'fail'];    
                }
            }else{
                return ['status' => 'fail'];
            }
        }else{
            return ['status' => 'fail'];    
        }
        return ['status' => 'fail'];
    }
    
    public function getStockDetails(){
        $products = Product::select('id')->orderBy('id', 'asc')->get()->toArray();
        $pids = array_column($products, 'id');
        
        $stock_details = [];
        foreach($pids as $id){
            
            $stock_details[$id] = $this->checkStock($id);
        }
        return $stock_details;
    }
    public function get_pl_quantity_sum_string($table_name = '')
    {
        $table_name = !empty($table_name) ? $table_name . '.' : '';
        $string = $table_name . "quantity_sold + " . $table_name . "quantity_adjusted + " . $table_name . "quantity_returned + " . $table_name . "mfg_quantity_used";
        
        return $string;
    }
    public function checkStock(Request $request){
        $location = $request->location;
        $business_id = 1;
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                  ->join('units', 'p.unit_id', '=', 'units.id')
                  ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                  ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                  ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                  ->where('p.business_id', $business_id)                  
                  ->where('vld.location_id', $location)
                  ->whereIn('p.type', ['single']);
                  $pl_query_string = $this->get_pl_quantity_sum_string('pl');
                  $products = $query->select(
                    DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                  JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                  WHERE transactions.status='received' AND transactions.location_id=vld.location_id 
                  AND (pl.variation_id=variations.id)) as stock_price"),
                    DB::raw("SUM(vld.qty_available) as stock"),
                    DB::raw("SUM(vld.mrp) as unit_price"),
                        'variations.sub_sku as sku',
                        'p.name as product',
                        'p.type',
                        'p.id as product_id',
                        'units.short_name as unit',
                        'p.enable_stock as enable_stock',
                        // 'variations.sell_price_inc_tax as unit_price',
                        'pv.name as product_variation',
                        'variations.name as variation_name',
                        'l.name as location_name',
                        'l.id as location_id',
                        'variations.id as variation_id'
                )->groupBy('variations.id', 'vld.location_id');

        // $stockDetail = $products->where('p.id', $filters['product_id'])->groupBy('l.id')->get()->toArray();
        $stockDetail = $products->groupBy('l.id')->get()->toArray();
        $productDetails = [];
        foreach ($stockDetail as $key => $value) {
            $productDetails[$value['product_id']] = $value;
        }
        return $productDetails;
        // $stock_detail = array_column(($stockDetail), 'stock');
        // $totalStock = 0;
        // foreach($stock_detail as $stock){
        //     if($stock){
        //         $totalStock += intval($stock);
        //     }
        // }
        //return $totalStock;
    }
    
    public function getRewardPointSetting(){
        return Business::first();
    }
    public function checkCartItemDeliveribility(Request $request){
        $location_id = $request->location;
        $request = $request->address;
        $postal_code = $request['postal_code'];
        $not_deliverable = 0;
        $business_location = BusinessLocation::where('id', $location_id)->first();
        $outer_pincode = explode(",", $business_location->outer_pincode);
        $inner_pincode = explode(",", $business_location->inner_pincode);
        $pincode = array_merge($outer_pincode, $inner_pincode);
        if(!in_array($postal_code, $pincode)){
            $not_deliverable = 1;
        }
        return ['not_deliverable' => $not_deliverable];
    }
    public function checkCartItemDeliveribilityFinal(Request $request){
        $location_id = $request->location;
        $pin = $request->address;
        $request = Address::find($pin)->toArray();
        $postal_code = $request['postal_code'];
        $not_deliverable = 0;
        $business_location = BusinessLocation::where('id', $location_id)->first();
        $outer_pincode = explode(",", $business_location->outer_pincode);
        $inner_pincode = explode(",", $business_location->inner_pincode);
        $pincode = array_merge($outer_pincode, $inner_pincode);
        if(!in_array($postal_code, $pincode)){
            $not_deliverable = 1;
        }
        return ['not_deliverable' => $not_deliverable];
    }
    // public function checkCartItemDeliveribility(Request $request){
    //     $request = $request->address;
    //     $postal_code = $request['postal_code'];
    //     $not_deliverable = 0;
    //     $cart_items = [];
    //     $cart = Cart::where('user_id', Auth::user()->id)->pluck('product_id', 'id');
    //     foreach($cart as $product){
            
    //         $location = ProductLocation::where('product_id', $product)->pluck('location_id');
    //         $business_location = BusinessLocation::where('id', $location)->where('is_active', 1)->first();
    //         if($business_location){
    //             $outer_pincode = explode(",", $business_location->outer_pincode);
    //             $inner_pincode = explode(",", $business_location->inner_pincode);
    //             $pincode = array_merge($outer_pincode, $inner_pincode);
                
    //             if(!in_array($postal_code, $pincode)){
    //                 $not_deliverable = 1;
    //                 $cart_items[$product] = 1;
    //             }else{
    //                 $cart_items[$product] = 0;
    //             }
    //         }
    //     }
    //     return ['not_deliverable' => $not_deliverable, 'cart_items' => $cart_items];
    // }
    public function removeShoppingAddress(Request $request){
        Cart::where('user_id', Auth::user()->id)->update(['address_id' => null]);
    }
    public function sendOtp($mobile){
        return rand(1000, 9999);
    }
    public function getOTP(Request $request){
        ContactTemp::where('mobile', $request->mobile)->delete();
        ContactTemp::where('email', $request->email)->delete();
        $mobileExist = Contact::where('mobile', $request->mobile)->first();        
        $emailExist = Contact::where('email', $request->email)->first();
        if($mobileExist && $emailExist && $mobileExist->id == $emailExist->id){            
            return ['status' => 'emai-mobile-exist'];
            // $otp = $this->sendOtp($request->mobile);
            // $mobileExist->otp = $otp;
            // $mobileExist->save();
            // return ['status' => 'success'];
        }elseif($mobileExist && $emailExist && $mobileExist->id != $emailExist->id){
            return ['status' => 'emai-mobile-exist'];
        }elseif($mobileExist && !$emailExist){
            return ['status' => 'mobile-exist'];
        }elseif($emailExist && !$mobileExist){
            return ['status' => 'emai-exist'];
        }
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contacts',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referer_code' => 'nullable'
        ]);
        $name = explode(' ', $request->name);
        
        $user = ContactTemp::create([
            'first_name' => $name[0],
            'last_name' => (count($name)>=2)?$name[1]:'',
            'name' => $request->name,
            'mobile' => $request->mobile,
            'type' => 'customer',
            'business_id' => 1,
            'referer_code' => $request->referer_code,
            'email' => $request->email,
            'contact_status' => 'active',
            'created_by' => 4,
            'password' => Hash::make($request->password),
        ]);
        $otp = $this->sendOtp($request->mobile);
        
        $user->otp = $otp;
        
        $message = "Dear customer, use this One Time Password {$otp} to log in to register youself. This OTP will be valid for the next 5 mins. VEEERE";
        if(env('APP_ENV') == 'production'){
            (new SmsController)->sendOtp($request->mobile, 1707167004384321891, $message);
        }
        $user->save();
        if (env('APP_ENV') == 'production') {
            return ['status' => 'success', 'env' => env('APP_ENV')];
        }else{
            return ['status' => 'success', 'otp' => $otp, 'env' => env('APP_ENV')];
        }
    }
    public function registerCustomer(Request $request){
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referer_code' => 'nullable',
            'otp' => 'required|numeric|digits:4'
        ]);
        $customer = ContactTemp::where('mobile', $request->mobile)
                    ->where('email', $request->email)
                    ->first();
        if($customer->otp == $request->otp){
            // 
            ContactTemp::where('mobile', $customer->mobile)->where('email', $customer->email)->delete();
            $name = explode(' ', $request->name);
        
            $user = User::create([
                'first_name' => $name[0],
                'last_name' => (count($name)>=2)?$name[1]:'',
                'name' => $request->name,
                'mobile' => $request->mobile,
                'type' => 'customer',
                'business_id' => 1,
                'referer_code' => $request->referer_code,
                'email' => $request->email,
                'contact_status' => 'active',
                'created_by' => 4,
                'password' => Hash::make($request->password),
            ]);
            $c_referer_code = (new OtherController)->generateRandomString(4);
            $user->c_referer_code = $user->id.$c_referer_code;
            $user->save();
            $table = "transaction_".$user->id."c";
            if(!Schema::hasTable($table)){
                $table_query = "CREATE TABLE IF NOT EXISTS `".$table."` (`id` int(10) NOT NULL AUTO_INCREMENT, `serial_no` varchar(200) DEFAULT NULL, `order_id` int(10) DEFAULT NULL, `description` text, `amt` int(10) DEFAULT NULL, `expiry_date` date DEFAULT NULL, `expiry` bigint(15) DEFAULT NULL, `date` date DEFAULT NULL, `trn_type` int(1) NOT NULL COMMENT 'cr:1,dr:2', `remaining` int(10) DEFAULT NULL, `status` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
                DB::statement($table_query);
            }
            $business = Business::first();
            if($business->enable_rp){
                if($request->referer_code){
                    //find agent
                    $agent = Agent_reg::where('referer_code', 'like', $request->referer_code)->first();
                    if(!Schema::hasTable($agent)){
                        $table_name = "transaction_".$agent->id."a";
                        if (Schema::hasTable($table_name)) {
                            $rp_name = $business->rp_name;
                            if($business->cus_rc_name){
                                $rp_name = $business->cus_rc_name;
                            }
                            $reward_points = $business->rc_registration;
                            $data = [
                                'user_id' => $user->id,
                                'description' => 'On registeration, you got '.$reward_points.' '.$rp_name,
                                'amt' => $reward_points,
                                'remaining' => $reward_points,
                                'expiry_date' => date('Y-m-d', strtotime('now') + 3600*24*$business->rc_expiry),
                                'expiry' => strtotime('now') + 3600*24*$business->rc_expiry,
                                'trn_type' => 1,
                                'status' => 1
                            ];
                            DB::table($table_name)->insert($data);
                        }
                    }
                }
            }
            // 
            return ['status' => 'success'];
        }else{
            return ['status' => 'otp-wrong'];
        }
    }
    public function loginRedirect(){
        return redirect()->route('login');
    }
    public function checkEmail(Request $request){
        return ['status' => User::where('email', $request->email)->where('verified', 1)->exists()];        
    }
    
    public function generateForgotPasswordOTP(Request $request){
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10|exists:contacts,mobile'
        ], ['mobile.exists' => 'Mobile does not exists']);
        if(User::where('mobile', $request->mobile)->count() == 0){
            return ['status' => 'error', 'msg' => 'Mobile does not exists'];    
        }
        $otp = rand(1000, 9999);
        if (env('APP_ENV') == 'production') {
            $message = "Dear customer, the one-time password to reset your password at Veeere is {$otp}. This OTP will expire in 5 minutes.";
            (new SmsController())->sendOtp($request->mobile, 1707166686295588975, $message);
        }
        // $otp = $this->sendOtp($request->mobile);
        User::where('mobile', $request->mobile)->update(['fp_otp' => $otp]);
        if (env('APP_ENV') == 'production') {
            return ['status' => 'success'];
        }else{
            return ['status' => 'success', 'otp' => $otp];
        }
    }
    public function changePasswordCustomer(Request $request){
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp' => 'required|numeric|digits:4'
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        
        if($request->otp == $user->fp_otp && $request->password != null && ($request->password == $request->password_confirmation
        )){
            $password = Hash::make($request->password);
            User::where('mobile', $request->mobile)->update(['password' => $password]);
            return ['status' => 'success'];
        }
        return ['status' => 'fail', 'msg' => 'Please try again'];
    }
    public function generateCodOtp(){
        $mobile = Auth::user()->mobile;
        $otp = $this->sendOtp($mobile);
        $user = ucfirst(Auth::user()->name);
        $n = 'VEEERE';
        if (env('APP_ENV') == 'production') {
            $message = "{$n}: Hi {$user}, the one time password to place COD order at Veeere is {$otp}. This OTP will expire in 5 minutes";
            $templateId = 1707166686248763199;
            (new SmsController())->sendOtp($mobile, $templateId, $message);
        }
        User::where('id', Auth::user()->id)->update(['order_otp' => $otp]);
        if (env('APP_ENV') == 'production') {
            return ['status' => 'success'];
        }else{
            return ['status' => 'success', 'otp' => $otp];            
        }
    }
    public function checkCodOtp(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if($user->order_otp == $request->orderOtp){
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'msg' => 'Wrong otp'];
        }
    }
    public function loginOtp(Request $request){        
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10|exists:contacts,mobile'
        ], [
            'mobile.exists' => 'Mobile does not exists'
        ]);
        if(User::where('mobile', $request->mobile)->count() == 0){
            return ['status' => 'error', 'msg' => 'Mobile does not exists'];    
        }
        // $otp = $this->sendOtp($request->mobile);
        $mobile = $request->mobile;
        $otp = rand(1000, 9999);
        if(env('APP_ENV') == 'production'){
            $message = "Dear customer, use this One Time Password {$otp} to log in to your Veeere account. This OTP will be valid for the next 5 mins. VEEERE";        
            $templateId = 1707166686289713086;                      
            $query = 'http://135.181.75.92/http-tokenkeyapi.php?authentic-key=36365353494e4449413931341667365080&senderid=VEEERE&route=1&number='.$mobile.'&message="'.urlencode($message).'"&templateid='.$templateId;
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $query,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }

        User::where('mobile', $request->mobile)->update(['fp_otp' => $otp]);
        if (env('APP_ENV') == 'production') {
            return ['status' => 'success'];
        }else{
            return ['status' => 'success', 'otp' => $otp];            
        }
    }
    public function checkLogin(Request $request){
        $this->validate($request, [
            'otp' => 'required|numeric|digits:4'
        ]);
        $mobile = $request->mobile;
        $otp = $request->otp;
        $user = User::where('mobile', $mobile)->where('fp_otp', $otp)->count();
        if($user){
            return ['status' => 'success'];
        }
        return ['status' => 'fail'];
    }
    public function submitOrderReview(Request $request){
        // dd($request->all());
        $data = $request->form;
        $ids = $data['ids'];
        $ratings = $data['ratings'];
        $comments = $data['comments'];
        for($i = 0; $i < count($data['ids']); $i++){
            ProductReview::create([
                'transaction_id' => $data['transaction_id'],
                'contact_id' => $data['user_id'],
                'product_id' =>  $ids[$i],
                'star' => $ratings[$i],
                'content' => $comments[$i]
            ]);
        }
        Transaction::where('id', $data['transaction_id'])->update(['order_review' => 1]);
        return ['status' => 'success'];
    }
    public function addShippingAddressToCartItem(Request $request, $address_id = null){
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            Cart::where('user_id', $user_id)->update([
                'address_id' => $address_id
            ]);
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->update([
                'address_id' => $address_id
            ]);
        }

    }
    public function addBillingAddressToCartItem(Request $request, $address_id = null){
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            Cart::where('user_id', $user_id)->update([
                'billing_address_id' => $address_id
            ]);
            return ['address' => Address::find($address_id)];
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->update([
                'billing_address_id' => $address_id
            ]);
        }
    }
    public function checkPincodeInSystem(Request $request){
        $postal_code = $request->pincode;
        $available = 0;
        $current_location = null;
        $business_location = BusinessLocation::get();
        foreach ($business_location as $key => $location) {
            $outer_pincode = explode(",", $location->outer_pincode);
            $inner_pincode = explode(",", $location->inner_pincode);
            if (in_array($postal_code, $outer_pincode) || in_array($postal_code, $inner_pincode)) {
                $available = 1;
                $current_location = $location->id;
            }
        }
        return ['available' => $available, 'location' => $current_location];
    }
    public function getSlidersMulLocation(Request $request){
        $location_id = $request->location;
        $sliders = Slider::where('status', '=', 1)->where('location_id', $location_id)->orderBy('order_record')->get();
        return ['sliders' => $sliders];
    }
    public function getTopBannerMulLocation(Request $request){
        $location_id = $request->location;
        return ['pbanner1_detail' => Promotionalbanner::where('location_id', $location_id)->where('position', 'top')->first()];
    }
    public function getBottomBannerMulLocation(Request $request){
        $location_id = $request->location;
        return ['pbanner2_detail' => Promotionalbanner::where('location_id', $location_id)->where('position', 'bottom')->first()];
    }
    public function getHomeOffersMulLocation(Request $request){
        $location_id = $request->location;
        $home_offer = Discount::whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
                        ->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))
                        ->where('location_id', $location_id)
                        ->where('show_on_front', 1)
                        ->where('is_active', 1)
                        ->pluck('photo_small');
        return [ 'home_offer' => $home_offer];
    }
    public function getOffersMulLocation(Request $request){
        $location_id = $request->location;
        $offers = Discount::with('category')
            ->where('is_active', 1)
            ->where('location_id', $location_id)
            ->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
            ->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))
            ->where('discount_category', 'veeere')
            ->get();
        return ['offers' => $offers];
    }
    public function getStreetAddressFrom(Request $request){
        // $curl = curl_init();
        // $latlng = $request->lat.','.$request->long;
        // $query = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&key=AIzaSyB3GgFD8YthniTjvyzOr-b6jw-CftRmy4o';
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => $query,
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // $response = curl_exec($curl);
        // curl_close($curl);
        // $data = get_object_vars(json_decode($response));
        // $result = get_object_vars($data['results'][0]);
        // foreach($result['address_components'] as $ac){
        //     $ac = get_object_vars($ac);
        //     echo '<pre>'; print_r($ac); echo '</pre>';
        //     if($ac['types'][0] == 'postal_code'){
        //         echo '<pre>'; print_r($ac['long_name']); echo '</pre>';
        //     }
        // }
        return [ 'postal_code' => 160036];
    }
    public function storeAddressInLocalStorage(Request $request){
        if(Auth::user()){
            $address = Address::where('user_id', Auth::user()->id)->get()->toArray();
            return ['address' => $address];
        }
        return ['address' => []];
    }
}
