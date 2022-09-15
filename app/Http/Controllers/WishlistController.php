<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Auth;
use Hash;
use Mail;
// use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index(){
        return Inertia::render('Wishlist/Index');
    }
}
