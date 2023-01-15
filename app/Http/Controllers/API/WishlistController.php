<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Category;

class WishlistController extends Controller
{

    public function index()
    {
        // $pids = Wishlist::where('user_id', Auth::user()->id)->get()->pluck('product_id');
        // return filter_products(Product::whereIn('id', $pids)->where('published', '1'))->get();   
        if(Auth::user()){
            return Wishlist::select('id', 'product_id')->with('product')->where('user_id', Auth::user()->id)->get();
        }
        return [];
    }

    public function store(Request $request)
    {
        if(Auth::check()){
            $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
            if($wishlist == null){
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::user()->id;
                $wishlist->product_id = $request->id;
                $wishlist->save();
            }
            return Wishlist::where('user_id', Auth::user()->id)->get();
        }
        return ['status' => 'failed', 'msg' => 'login required'];
    }

    public function destroy($id, Request $request)
    {
        $wishlist = Wishlist::findOrFail($id);
        if($wishlist!=null){
            if(Wishlist::destroy($id)){
                return Wishlist::where('user_id', Auth::user()->id)->get();
            }
        }
        return ['status' => 'failed', 'msg' => 'login required'];
    }
    public function deleteWishlistItem(Request $request){
        $productId = $request->id;
        Wishlist::where('user_id', Auth::user()->id)->where('product_id', $productId)->delete();
        return ['status' => 'success'];
    }
}
