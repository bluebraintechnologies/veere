<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Product;
use App\Utils\ModuleUtil; 
use Illuminate\Http\Request;


class TaxonomyController extends Controller
{
    protected $moduleUtil;

    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }
    public function index()
    {
        $category_type = request()->get('type');
        
        $business_id = 1;
        // dd(Category::where('business_id', $business_id)->get());
        $category = Category::where('business_id', $business_id)
                        // ->where('category_type', $category_type)
                        ->where('parent_id', 0)
                        ->select(['name', 'short_code', 'description', 'id', 'parent_id'])->orderBy('name', 'asc')->get();

        // $module_category_data = $this->moduleUtil->getTaxonomyData($category_type);

        return $category;
    }
}
