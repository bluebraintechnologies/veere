<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
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
    public function getBestSellerProducts(){
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
                    'products.product_custom_field4');
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
                    'products.product_custom_field4',
                   
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
                    'products.product_custom_field4',
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
                    'products.product_custom_field4',
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
                    'products.product_custom_field4',
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
                    'products.product_custom_field4',
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
                    'products.product_custom_field4',
                    )->groupBy('products.id')->get();
        dd($products);
        echo '<pre>'; print_r($query); echo '</pre>'; die;
    }
    public function getWishlistItems(){
        $wishlists = Wishlist::select('product_id')->get()->toArray();
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
                    'products.product_custom_field4',
                    )->get();
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
        if($form['password'] && Hash::make($form['password']) != $contact->password){
            return ['status' => 'fail'];
        }
        return ['status' => 'success'];
    }
}
