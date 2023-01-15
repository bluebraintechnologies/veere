<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Deals;
use App\Models\Business;
use App\Models\Discount;
use App\Models\Enquiry;
use App\Models\Promotionalbanner;
use App\Models\ProductReview;
use App\Models\TransactionSellLine;
use App\Models\Variation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasssword;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function about(){
        return Inertia::render('Page/About');
    }
    public function terms(){
        return Inertia::render('Page/Terms');
    }
    public function privacyPolicy(){
        return Inertia::render('Page/PrivacyPolicy');
    }
    public function returnPolicy(){
        return Inertia::render('Page/ReturnPolicy');
    }
    public function faqs(){
        return Inertia::render('Page/Faqs');
    }
    public function contactus(){
        return Inertia::render('Page/ContactUs');
    }
    public function saveEnquiry(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|numeric|digits:10',
            'message' => 'required|string'
        ]);
        Enquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'message' => $request->message,
        ]);
        return ['status' => 'success'];
    }
    public function business(){
        return Business::first();
    }
}