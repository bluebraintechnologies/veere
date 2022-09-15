<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
                                    ->select(['products.category_id',  'c1.name as category', 'c1.slug'])->orderBy('category', 'asc')->distinct('category_id')->get()->toArray();
        $featured_products = [];
        foreach ($featured_category as $key => $value) {
            $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.category_id', $value['category_id'])
                        ->where('products.is_inactive', 0);
            $fps = $query->select(
                                'products.id',
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
                                'v.sell_price_inc_tax as price')->orderBy('products.name', 'asc')->get()->toArray();
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
                                        'v.sub_sku',
                                        'v.sell_price_inc_tax as price')->inRandomOrder()->limit(8)->get();

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
                                        'v.sub_sku',
                                        'v.sell_price_inc_tax as price')->inRandomOrder()->limit(8)->get();

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
                                        'v.sub_sku',
                                        'v.sell_price_inc_tax as price')->inRandomOrder()->limit(8)->get();
        
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
                                        'v.sub_sku',
                                        'v.sell_price_inc_tax as price')->inRandomOrder()->limit(8)->get();
        
        return Inertia::render('Home/Index', compact('sliders', 'categories', 'featured_products', 'featured_category', 'top_selling', 'top_rated', 'best_sellers', 'new_arrivals'));
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
        $product = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            ->where('products.new_tag', 1)
                            ->where('products.slug',  $slug)
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
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price')->first();
        return Inertia::render('Product/Detail', compact('product'));
    }

    public function category($slug = null)
    {
        $category = Category::where('slug', $slug)->first();
        $products = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                            ->join('variations as v', 'v.product_id', '=', 'products.id')
                            ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                            ->where('products.type', '!=', 'modifier')
                            ->where('products.not_for_selling', 0)
                            ->where('products.new_tag', 1)
                            ->where('products.category_id',  $category->id)
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
                                    'v.sub_sku',
                                    'v.sell_price_inc_tax as price')->get();
        return Inertia::render('Product/Category', compact('products', 'category'));
    }
    public function userProfileUpdate(Request $request){
        $contact = Auth::user();
        // dd($contact);
        // dd($request->all());
        // if($request->password && Hash::make($request->password) != $contact->password){
        //     $this->data['user'] = Auth::user();
        //     $this->data['errors'] = 'Password is incorrect';
        //     return Inertia::render('User/Profile', $this->data);
        // }
        $contactId = $contact->id;
        if($request->new_password){
            $password = Hash::make($request->new_password);
        }else{
            $password = $contact->password;
        }
        // echo $contact->password; echo '<br>';
        // echo $password; die;
        Contact::where('id', $contactId)->update([
            'name' => $request->name,
            'mobile' => $request->phone,
            'password' => $password
        ]);
        // $this->data['smenu'] = 'profile';
        // $this->data['user'] = Auth::user();
        return back();
    }
}
