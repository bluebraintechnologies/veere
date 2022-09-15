<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Mail;
use Illuminate\Auth\Events\PasswordReset;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        return Inertia::render('Home/Index');
    }
    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('Auth/login');
    }
}
