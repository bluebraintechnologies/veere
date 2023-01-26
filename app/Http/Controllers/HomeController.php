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
use App\Models\Slider;
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
            ->whereDate('starts_at', '<=', date('Y-m-d', strtotime('now')))
            ->whereDate('starts_at', '>=', date('Y-m-d', strtotime('now')))
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
    public function checkLocationBasedStock($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
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
                        'variations.id as variation_id'
                )->groupBy('variations.id', 'vld.location_id');

        // $stockDetail = $products->where('p.id', $filters['product_id'])->groupBy('l.id')->get()->toArray();
        $stockDetail = $products->groupBy('l.id')->get()->toArray();
        $productDetails = [];
        foreach ($stockDetail as $key => $value) {
            $productDetails[$value['product_id']] = $value;
        }
        return $productDetails;
    }
    public function featuredProducts($location_id = null){
        if(!isset($location_id)){
            $location_id = 1;
        }
        $business_id = 1;
        $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
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
                        'variations.id as variation_id'
                )->groupBy('variations.id', 'vld.location_id');

        // $stockDetail = $products->where('p.id', $filters['product_id'])->groupBy('l.id')->get()->toArray();
        $stockDetail = $products->groupBy('l.id')->get()->toArray();
        $productDetails = [];
        foreach ($stockDetail as $key => $value) {
            $productDetails[$value['product_id']] = $value;
        }
        return $productDetails;
    }
    public function index(){        
        $isUserLogged = 0;
        $loggedinUserDetail = null;
        if (Auth::check()) {
            $isUserLogged = 1;
            $loggedinUserDetail = Auth()->user();
        }
        $categories = Category::where('business_id', 1)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'slug', 'id', 'image'])->orderBy('name', 'asc')->get();
        
        $footerImages = DB::table('footer_images')->orderBy('id', 'desc')->get();
        return Inertia::render('Home/Index', compact('footerImages', 'categories', 'isUserLogged', 'loggedinUserDetail'));
    }
    public function search(Request $request){
        if($request->keyword){
            $type = 'search';
            $keyword = $request->keyword;            
            $submenu = 'Search';
            return Inertia::render('Product/search', compact('submenu', 'keyword', 'type'));
        }elseif($request->ot == 'deal'){
            $type = 'deal';
            $d = $this->getDealItems();
            $dp = $d['products'];
            
            $submenu = 'Deal';
            return Inertia::render('Product/SearchDeal', compact('submenu', 'type'));
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
        $loggedinUserDetail = null;
        if (Auth::check()) {
            $isUserLogged = 1;
           $loggedinUserDetail = Auth()->user();
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
                                    'products.legal_disclaimer',
                                    'products.short_description',
                                    'products.standard_product_discount_type',
                                    'products.standard_discount',
                                    'products.discount_type',
                                    'products.percent_discount',
                                    'products.flat_discount',
                                    'products.discount_start_date',
                                    'products.discount_end_date',
                                    'products.product_custom_field2 as hsn_code',
                                    'products.product_custom_field3 as origin',
                                    'products.product_custom_field4 as manufacturer',
                                    'products.product_custom_field5 as material_features',
                                    'products.self_life as self_life',
                                    'products.self_life_type as self_life_type',
                                    'products.handling_time as handling_time',
                                    'products.handling_time_type as handling_time_type',
                                    'products.weight',
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price'
                                    )->first();
        
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
                                    'products.standard_product_discount_type',
                                    'products.standard_discount',
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
        $productReviews = ProductReview::with('contact')->where('product_id', '=', $product->id)->orderBy('id', 'desc')->get();
        // dd($productReviews);
        $business = DB::table('business')->select('ptwitter', 'pfacebook', 'plinkedin', 'pwhatsapp', 'ppinterest', 'pemail')->first();
        return Inertia::render('Product/Detail', compact('business', 'product', 'isUserLogged', 'productReviews', 'relatedProduct', 'legal_disclaimer', 'deal_end_date_timestamp', 'available_stock', 'loggedinUserDetail'));
    }

    public function category($slug = null)
    {
        $isUserLogged = 0;
        $loggedinUserDetail = null;
        if (Auth::check()) {
            $isUserLogged = 1;
            $loggedinUserDetail = Auth()->user();
        }
        $category = Category::where('slug', $slug)->first();
        $categories = Category::with('childrenCategories')->where('parent_id', 0)->get();        
        return Inertia::render('Product/Category', compact( 'category', 'isUserLogged', 'categories', 'loggedinUserDetail'));
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
            // 'mobile' => $request->phone,
            // 'password' => $password,
            'gender' => $request->gender,
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
        $loggedinUserDetail = null;
        if (Auth::check()) {
            $isUserLogged = 1;
            $loggedinUserDetail = Auth()->user();
        }
        
        return Inertia::render('Product/GroceryList', compact('isUserLogged', 'loggedinUserDetail'));
    }
    public function feedback($oid = null){
        $order = Transaction::with(['sell_lines', 'payment_lines'])->where('type', 'sell')->where('invoice_no', base64_decode($oid))->first();
        if(!$order) {
            return redirect()->route('home');
        }
        if($order->order_review || $order->shipping_status == 'delivered'){
            return redirect()->route('home');
        }

        $this->data['order'] = $order;
        return Inertia::render('Home/Feedback', $this->data);
        dd($order);
    }
}
