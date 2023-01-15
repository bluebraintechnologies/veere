<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Discount; 
use App\Models\Product; 
use Auth;

class OffersController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d', strtotime('now'));
        $records = Discount::where('is_active', 1)
                    ->whereDate('starts_at', '<=', $today)
                    ->whereDate('ends_at', '>=', $today)
                    ->get();
        return ['offers' => $records];
    }
    public function details(Request $request){
        $offer = $request->offer;
        // id" => 7
        // "name" => "10% off on healthy flour"
        // "title" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy"
        // "code" => "VEERE10HF"
        // "discount_base" => "cart_base"
        // "coupan_uses" => 2
        // "products" => "[51,107,108,109,111,112,113,114,115,116,117,118,119,120,121,132,186,201]"
        // "photo" => "8647offer-banner-06.jpg"
        // "business_id" => 1
        // "brand_id" => null
        // "category_id" => 3
        // "location_id" => null
        // "priority" => 5
        // "discount_type" => "percentage"
        // "discount_amount" => 10
        // "starts_at" => "2022-10-05T00:00:00.000000Z"
        // "ends_at" => "2022-10-20T00:00:00.000000Z"
        // "blend_product_discount" => 1
        // "blend_with_offer" => null
        // "blend_with_deals" => 1
        // "is_active" => 1
        // "applicable_in_spg" => 0
        // "applicable_in_cg" => 1
        // "created_at" => "2022-10-05T16:49:21.000000Z"
        // "updated_at" => "2022-10-05T19:44:04.000000Z"
        $productsIds = json_decode($offer['products']);
        
        $query = Product::leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                        ->join('variations as v', 'v.product_id', '=', 'products.id')
                        ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                        ->where('products.type', '!=', 'modifier')
                        // ->where('products.featured', 1)
                        ->where('products.not_for_selling', 0)
                        ->where('products.is_inactive', 0)
                        ->whereIn('products.id', $productsIds)
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
        return ['products' => $productsIds, 'offerProducts' => $fps];
    }
}