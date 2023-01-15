<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Business;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    public function index(){
        $isUserLogged = 0;
        $loggedinUserDetail =  null;
        if (Auth::check()) {
            $isUserLogged = 1;
            $loggedinUserDetail = Auth()->user();
        }
        $business = Business::find(1);
        
        return Inertia::render('Checkout/Cart', compact('isUserLogged', 'loggedinUserDetail', 'business'));
    }
}
