<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
// use App\Models\Media;
use App\Models\ProductReview;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasssword;

class HomeController extends Controller
{
    public function getAuthUser(){
        if(Auth::check()){
            return Auth::user()->id;
        }else{
            return ['status' => ''];
        }
    }
    public function index(){
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
                                'v.sell_price_inc_tax as price',)->orderBy('products.name', 'asc')->get()->toArray();
            array_push($featured_products, ['slug' => $value['slug'], 'products' => $fps]);
        }
        $best_sellers = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.is_inactive', 0)
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
                                        'v.sell_price_inc_tax as price',)->orderBy('products.new_tag', 'desc')->limit(8)->get();

        $top_rated = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
                                ->where('products.is_inactive', 0)
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
                                        'v.sell_price_inc_tax as price',)->orderBy('products.new_tag', 'desc')->limit(8)->get();

        $top_selling = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                                ->join('variations as v', 'v.product_id', '=', 'products.id')
                                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                                ->where('products.type', '!=', 'modifier')
                                ->where('products.not_for_selling', 0)
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
                                        'v.sell_price_inc_tax as price',)->orderBy('products.new_tag', 'desc')->limit(8)->get();
        
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
                                        'v.sell_price_inc_tax as price',)->orderBy('products.new_tag', 'desc')->limit(8)->get();
        
        return Inertia::render('Home/Index', compact('sliders', 'categories', 'featured_products', 'featured_category', 'top_selling', 'top_rated', 'best_sellers', 'new_arrivals', 'isUserLogged'));
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('Auth/login');
    }

    public function product($slug = null)
    {
        $isUserLogged = 0;
        if (Auth::check()) {
            $isUserLogged = 1;
        }
        // $product = Product::with('media')->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
        //                     ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
        //                     ->join('variations as v', 'v.product_id', '=', 'products.id')
        //                     ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
        //                     ->where('products.type', '!=', 'modifier')
        //                     ->where('products.not_for_selling', 0)
        //                     ->where('products.new_tag', 1)
        //                     ->where('products.slug',  $slug)
        //                     ->where('products.is_inactive', 0)
        //                     ->select('products.id',
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
        //                             'v.sell_price_inc_tax as price',)->first();
        $p = Product::where('products.slug',  $slug)->first(); 
        // dd($p->category_id);
        $relatedProduct = Product::with('media')->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            // ->where('products.new_tag', 1)
                            ->where('products.category_id',  $p->category_id)
                            ->where('products.id', "!=", $p->id)
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
                                    'v.sell_price_inc_tax as price',)
                            ->limit(4)
                            ->get();
        // dd($relatedProduct);
        $product = Product::with(['brand', 'unit', 'category', 'sub_category', 'product_tax', 'variations', 'product_locations', 'warranty', 'media'])
                                    ->where('products.slug',  $slug)->first();
        // dd($product);
        $productReviews = ProductReview::where('product_id', '=', $product->id)->orderBy('id', 'desc')->get();
        return Inertia::render('Product/Detail', compact('product', 'isUserLogged', 'productReviews', 'relatedProduct'));
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
}
