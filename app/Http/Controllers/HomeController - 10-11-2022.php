<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Deals;
use App\Models\Business;
use App\Models\Discount;
use App\Models\Promotionalbanner;
use App\Models\ProductReview;
use App\Models\TransactionSellLine;
use App\Models\Variation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasssword;
use App\Mail\UserOrder;
use App\Models\Transaction;

use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function getAuthUser(){
        if(Auth::check()){
            return Auth::user()->id;
        }else{
            return ['status' => ''];
        }
    }
    public function getDealItems(){
        $deals = Deals::where('is_active', 1)
            ->whereDate('starts_at', '=', date('Y-m-d', strtotime('now')))
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
                            'discount_type' => $deal['discount_type'],
                            'discount_amount' => $deal['discount_amount'],
                            'blend_product_discount' => $deal['blend_product_discount'],
                            'blend_with_offer' => $deal['blend_with_offer'],
                            'deal_start_date' => date('Y-m-d', strtotime($deal['starts_at'])),
                            'deal_end_date' => date('Y-m-d', strtotime($deal['starts_at'])),
                            'deal_end_date_timestamp' => date('Y-m-d H:i:s', strtotime($deal['starts_at'])+24*3600-1),
                        ];
                    }
                }
                $toDealProduct[] = array_slice($ptest, 0, 1)[0];
            }
        }
        return ['products' => $products, 'toDealProduct' => $toDealProduct];     
    }
    public function index(){
        // dd(Product::pluck('id'));
        // foreach(Product::get() as $p){
        //     $img = ["1.jpeg","2.jpeg","3.jpeg","4.jpeg"];
        //     $images = 
        //         [
        //             0 => $p['image']
        //         ];
        //     $nimg = array_merge($images, $img);
            
        //     $result = Product::where('id', $p['id'])->update([
        //         'images' => json_encode($nimg)
        //     ]);
        // }
        // dd($result);
        $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                       
                        ->where('products.is_inactive', 0)
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
                                'products.sku',
                                'products.image',
                                'products.discount_type',
                                'products.percent_discount',
                                'products.flat_discount',
                                'products.discount_start_date',
                                'products.discount_end_date',
                                'v.sub_sku',
                                'v.sell_price_inc_tax as price')->orderBy('products.name', 'asc')->get()->toArray();

        $isUserLogged = 0;
        if (Auth::check()) {
            $isUserLogged = 1;
        }
        $sliders = [
            ['link' => 'https://veeere.com', 'image' => '/media/1.jpg'],
            ['link' => 'https://veeere.com', 'image' => '/media/2.jpg'],
            ['link' => 'https://veeere.com', 'image' => '/media/3.jpg'],
        ];

        $categories = Category::where('business_id', 1)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'slug', 'id'])->orderBy('name', 'asc')->get();
        $featured_category = Product::where('products.business_id', 1)
                                    ->where('products.type', '!=', 'modifier')
                                    ->where('products.featured', 1)
                                    ->where('products.not_for_selling', 0)
                                    ->where('products.is_inactive', 0)
                                    ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                    ->select(['products.category_id',  'c1.name as category', 'c1.slug'])->orderBy('category', 'asc')
                                    ->distinct('category_id')
                                    ->get()
                                    ->toArray();
        $featured_products = [];
       
        foreach ($featured_category as $key => $value) {
            $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.category_id', $value['category_id'])
                        ->where('products.is_inactive', 0)
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
                                'products.sku',
                                'products.image',
                                'products.discount_type',
                                'products.percent_discount',
                                'products.flat_discount',
                                'products.discount_start_date',
                                'products.discount_end_date',
                                'v.sub_sku',
                                'v.sell_price_inc_tax as price')->orderBy('products.name', 'asc')->get()->toArray();
                                
            

            array_push($featured_products, ['slug' => $value['slug'], 'products' => $fps]);
        }
        $best_sell = TransactionSellLine::selectRaw('COUNT(*) s, product_id')->groupBy('product_id')->orderBy('s', 'desc')->limit(8)->get()->toArray();
        $best_sell_pid = array_column($best_sell, 'product_id');
        $best_sellers = [];
        foreach($best_sell_pid as $pid){
            $best_sellers[] = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                    ->join('variations as v', 'v.product_id', '=', 'products.id')
                                    ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                    ->where('products.type', '!=', 'modifier')
                                    ->where('products.not_for_selling', 0)
                                    ->where('products.is_inactive', 0)
                                    ->where('products.id', $pid)
                                    ->select('products.id',
                                            'products.new_tag',
                                            'products.sale_start',
                                            'products.sale_end',
                                            'products.slug',
                                            'products.name',
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
                                            'v.sell_price_inc_tax as price')->orderBy('products.new_tag', 'desc')->first();

        }

        $top_rating = ProductReview::selectRaw('AVG(star) rating, product_id')
            ->groupBy('product_id')
            ->orderBy('rating', 'desc')
            ->get()->toArray();
        $product_rating = array_combine(array_column($top_rating, 'product_id'), array_column($top_rating, 'rating'));
        $top_rated = [];
        foreach($product_rating as $key => $rating){
            $p = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.is_inactive', 0)
                                ->where('products.id', $key)
                                ->select('products.id',
                                        'products.new_tag',
                                        'products.sale_start',
                                        'products.sale_end',
                                        'products.slug',
                                        'products.name',
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
                                        'v.sell_price_inc_tax as price')->orderBy('products.new_tag', 'desc')->first();
            $p['rating'] = $rating;
            $top_rated[] = $p;
        }
        
        $top_sell = TransactionSellLine::selectRaw('SUM(quantity) s, product_id')->groupBy('product_id')->orderBy('s', 'desc')->limit(8)->get();
        $top_sell_pid = array_column($top_sell->toArray(), 'product_id');
        $top_selling = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.is_inactive', 0)
                                ->whereIn('products.id', $top_sell_pid)
                                ->select('products.id',
                                        'products.new_tag',
                                        'products.sale_start',
                                        'products.sale_end',
                                        'products.name',
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
                                        'v.sell_price_inc_tax as price')->orderBy('products.new_tag', 'desc')->limit(8)->get();
        
        $new_arrivals = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.new_tag', 1)
                                ->where('products.is_inactive', 0)
                                ->select('products.id',
                                        'products.new_tag',
                                        'products.sale_start',
                                        'products.sale_end',
                                        'products.name',
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
                                        'v.sell_price_inc_tax as price')->orderBy('products.updated_at', 'desc')->limit(8)->get();
        
        $offers = Business::select('banner_1', 'banner_2', 'banner_3')->first()->toArray();
        $home_offer = [];
        foreach($offers as $offer){
            if($offer>0){
                if(Discount::where('id', $offer)->where('is_active', 1)->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))->exists()){
                    $dis = Discount::where('id', $offer)->select('photo')->first()->toArray();
                    $home_offer[] = $dis['photo'];
                }
            }
        }
        return Inertia::render('Home/Index', compact('sliders', 'categories', 'featured_products', 'featured_category', 'top_selling', 'top_rated', 'best_sellers', 'new_arrivals', 'isUserLogged', 'home_offer'));
    }
    public function search(Request $request){
        if($request->keyword){
            $keyword = $request->keyword;
            $searchTerm = $keyword;
            $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
            $searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);

            $searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);
            $products = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
                        // ->where('products.slug', 'like', '%'.$keyword.'%')
                        // ->where('products.name', 'like', '%'.$keyword.'%')
                        // ->where('c1.slug', 'like', '%'.$keyword.'%')
                        // ->where('c1.name', 'like', '%'.$keyword.'%')
                        ->where(function ($q) use ($searchValues) {
                            foreach ($searchValues as $value) {
                                $q->orWhere('products.slug', 'like', "%{$value}%");
                                $q->orWhere('products.name', 'like', "%{$value}%");
                                $q->orWhere('c1.slug', 'like', "%{$value}%");
                                $q->orWhere('c1.name', 'like', "%{$value}%");
                            }
                        })
                        ->select('products.id',
                                'products.new_tag',
                                'products.sale_start',
                                'products.sale_end',
                                'products.name',
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
                                'v.sell_price_inc_tax as price')
                        ->orderBy('products.updated_at', 'desc')
                        ->get();
            $submenu = 'Search';
            return Inertia::render('Product/search', compact('products', 'submenu', 'keyword'));
        }elseif($request->ot == 'deal'){
            $d = $this->getDealItems();
            $dp = $d['products'];
            $products = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
                        ->whereIn('products.id', $dp)
                        ->select('products.id',
                                'products.new_tag',
                                'products.sale_start',
                                'products.sale_end',
                                'products.name',
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
                                'v.sell_price_inc_tax as price')
                        ->orderBy('products.updated_at', 'desc')
                        ->get();
            $submenu = 'Deal';
            return Inertia::render('Product/SearchDeal', compact('products', 'submenu'));
        }else{
            return redirect('/');
        }

    }
    public function offers(){
        $offers = Discount::with('category')
            ->where('is_active', 1)
            ->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
            ->whereDate('ends_at', '>=', date('Y-m-d', strtotime('now')))
            ->where('discount_category', 'veeere')
            ->get();
        return Inertia::render('Product/offers', compact('offers'));
        // return Inertia::render('Product/offers');
    }
    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('Auth/login');
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
        $stockDetail = $products->where('p.id', $filters['product_id'])->groupBy('l.id')->get()->toArray();
        $stock_detail = array_column(($stockDetail), 'stock');
        $totalStock = 0;
        foreach($stock_detail as $stock){
            if($stock){
                $totalStock += intval($stock);
            }
        }
        return $totalStock;
    }
    public function product($slug = null)
    {
        $isUserLogged = 0;
        if (Auth::check()) {
            $isUserLogged = 1;
        }
        
        
        $product = Product::with('media')->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            // ->where('products.new_tag', 1)
                            ->where('products.slug', 'like',  $slug)
                            ->where('products.is_inactive', 0)
                            ->select('products.id',
                                    'products.new_tag',
                                    'products.sale_start',
                                    'products.sale_end',
                                    'products.name',
                                    'products.slug',
                                    'c1.name as category',
                                    'c1.id as category_id',
                                    'c1.slug as category_slug',
                                    'c2.name as sub_category',
                                    'products.sku',
                                    'products.image',
                                    'products.images',
                                    'products.product_description',
                                    'products.short_description',
                                    'products.discount_type',
                                    'products.percent_discount',
                                    'products.flat_discount',
                                    'products.discount_start_date',
                                    'products.discount_end_date',
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price')->first();
        // $p = Product::where('products.slug',  $slug)->first(); 
        // dd($p->category_id);
        // dd($product);
        $deal_end_date_timestamp = date('Y-m-d H:i:s', strtotime('now'));
        $d = $this->getDealItems();
        $dp = $d['products'];
        $dpd = $d['toDealProduct'];
        $rates = ProductReview::selectRaw('AVG(star) rating, product_id')
                    ->whereIn('product_id', $dp)
                    ->groupBy('product_id')
                    ->get()->toArray();
        $proRating = array_combine(array_column($rates, 'product_id'), array_column($rates, 'rating'));
        
        $ac = array_combine(array_column($dpd, 'id'), array_column($dpd, 'deal_end_date_timestamp'));
        if(isset($ac[$product->id])){
            $deal_end_date_timestamp = $ac[$product->id];
        }
        $available_stock = $this->checkStock($product->id);
        $relatedProduct = Product::with('media')->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            // ->where('products.new_tag', 1)
                            ->where('products.category_id',  $product->category_id)
                            ->where('products.id', "!=", $product->id)
                            ->where('products.is_inactive', 0)
                            ->select('products.id',
                                    'products.new_tag',
                                    'products.sale_start',
                                    'products.sale_end',
                                    'products.name',
                                    'products.slug',
                                    'c1.name as category',
                                    'c1.slug as category_slug',
                                    'c2.name as sub_category',
                                    'products.sku',
                                    'products.image',
                                    'products.product_description',
                                    'products.discount_type',
                                    'products.percent_discount',
                                    'products.flat_discount',
                                    'products.discount_start_date',
                                    'products.discount_end_date',
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price')
                            ->limit(4)
                            ->get();
        // dd($relatedProduct);
        // $product = Product::with(['brand', 'unit', 'category', 'sub_category', 'product_tax', 'variations', 'product_locations', 'warranty', 'media'])
            //                             ->where('products.slug',  $slug)
            //                             ->select('products.id',
            //                             'products.new_tag',
            //                             'products.sale_start',
            //                             'products.sale_end',
            //                             'products.name',
            //                             'products.slug',
            //                             'c1.name as category',
            //                             'c1.slug as category_slug',
            //                             'c2.name as sub_category',
            //                             'products.sku',
            //                             'products.image',
            //                             'products.product_description',
            //                             'products.discount_type',
            //                             'products.percent_discount',
            //                             'products.flat_discount',
            //                             'products.discount_start_date',
            //                             'products.discount_end_date',
            //                             'v.sub_sku',
            //                             'v.sell_price_inc_tax as price',)
            //                             ->first();
        // dd($product);
        $this->data['product_bought'] = Transaction::where('transactions.shipping_status', 'like', 'delivered')
                    ->where('transaction_sell_lines.product_id', '=', $product->id)
                    ->join('transaction_sell_lines', 'transaction_sell_lines.transaction_id', '=', 'transactions.id')->count();
        
        $legal_disclaimer = DB::table('business')->select('legal_disclaimer')->first();
        $productReviews = ProductReview::where('product_id', '=', $product->id)->orderBy('id', 'desc')->get();

        return Inertia::render('Product/Detail', compact('product', 'isUserLogged', 'productReviews', 'relatedProduct', 'legal_disclaimer', 'deal_end_date_timestamp', 'available_stock'));
    }

    public function category($slug = null)
    {
        $isUserLogged = 0;
        if (Auth::check()) {
            $isUserLogged = 1;
        }
        $category = Category::where('slug', $slug)->first();
        $categories = Category::with('childrenCategories')->where('parent_id', 0)->get();        
        return Inertia::render('Product/Category', compact( 'category', 'isUserLogged', 'categories'));
    }
    public function userProfileUpdate(Request $request){
        $contact = Auth::user();
        $contactId = $contact->id;
        $fileName = $contact->image;
        if($request->avatar){
            $request->validate([
                'avatar' => 'required|mimes:jpg,jpeg,png|max:2048'
            ]);
            $file = $request->file('avatar') ;
            $fileName = time().'_'.$file->getClientOriginalName() ;
            // $destinationPath = public_path().'/avatars' ;
            $destinationPath = 'avatars' ;
            $file->move($destinationPath,$fileName);
        }
       
        if($request->new_password){
            $password = Hash::make($request->new_password);
        }else{
            $password = $contact->password;
        }
        Contact::where('id', $contactId)->update([
            'name' => $request->name,
            'mobile' => $request->phone,
            'password' => $password,
            'image' => $fileName
        ]);
        return back();
    }
    public function email(){
        $data['name'] = 'anand prakash verma';
        // echo $name; die;
        $data['resetLink'] = Hash::make(base64_encode('avanandverma8@gmail.com'));
        $data['email'] = 'avanandverma8@gmail.com';
        Mail::to('avanandverma8@gmail.com')->send(new ForgotPasssword($data));
        return 'Email was sent';
    }
    public function resetPassword(Request $request){
        dd($request->all());
    }
    public function isAccountVerified(Request $request){
        if(is_null($request->email)){
            return ['status' => 'no-email'];
        }elseif(Contact::whereNotNull('email')->where('email', $request->email)->where('verified', 1)->exists())
            return ['status' => 'not-verified'];
        elseif(Contact::where('email', $request->email)->where('verified', 2)->exists()){
            return ['status' => 'verified'];
        }
    }
    public function groceryListFront(){
        $isUserLogged = 0;
        if (Auth::check()) {
            $isUserLogged = 1;
        }
        return Inertia::render('Product/GroceryList', compact('isUserLogged', 'isUserLogged'));
    }
}
