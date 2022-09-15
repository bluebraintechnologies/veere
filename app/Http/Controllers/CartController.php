<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(){
       
        return Inertia::render('Checkout/Cart');
    }
}
