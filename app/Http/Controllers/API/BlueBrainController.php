<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\BusinessSetting;
use App\Models\Coupon;
use App\Models\Upload;
use App\Models\Currency;
use App\Models\Query;
use Cookie;
use Illuminate\Support\Str;
use Cache;

class BlueBrainController extends Controller
{
    public function getNavigations()
    {
        $nav_categories = //Cache::rememberForever('nav_categories', function () {
            //return 
            Category::where('parent_id', 0)->with('childrenCategories')->get();
        //});
        return $nav_categories;
    }

    public function getSettings()
    {
        $homesettings = BusinessSetting::get()->toArray();
        
        $settings = [];
        foreach ($homesettings as $key => $value) {
            $settings[$value['type']] = json_decode($value['value']);
        }
        $currency = Currency::where('id', $settings['system_default_currency'])->first();
        $settings['active_currency'] = $currency;
        return $settings;
    }

    public function getAssets()
    {
        $assets = Cache::remember('uplaoded_assets', 400, function () {
            return Upload::all();
        });

        return $assets;
    }

    public function getProducts()
    {
        $assets = Cache::remember('all_products', 86400, function () {
            return Product::all();
        });

        return $assets;
    }

    public function getCategories()
    {
        $assets = Cache::remember('all_categories', 86400, function () {
            return Category::all();
        });

        return $assets;
    }

    public function submitQuery(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required:email:dns',
            'phone' => 'required',
            'message' => 'required',
        ]);
        Query::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);
        return ['success'];
    }
}