<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\TransactionSellLines;
use App\Models\Address;
use App\Models\Wishlist;
use Cookie;
use Illuminate\Support\Str;
use Mail;
use Cache;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->data['smenu'] = 'dashboard';
        $this->data['activemenu'] = '';
        
        // $this->data['user'] = auth()->user();
    }

    public function index()
    {
        if(Auth::user()->type == 'customer'){
             $this->data['orders'] =Transaction::select('id', 'payment_status', 'status', 'invoice_no', 'final_total', 'shipping_status')->with('sell_lines')->where('type', 'sell')->where('contact_id', Auth::user()->id)->where('shipping_status', '!=', 'delivered')->get();
            $this->data['synop'] = [
                'orders' => Transaction::where('type', 'sell')->where('contact_id', Auth::user()->id)->count(),
                'saving' => Transaction::where('type', 'sell')->where('contact_id', Auth::user()->id)->sum('discount_amount'),
                'offers' => Transaction::where('type', 'sell')->where('contact_id', Auth::user()->id)->where('discount_amount', '>', 0)->count(),
                'wishlists' => Wishlist::where('user_id', Auth::user()->id)->count(),
            ];
            return Inertia::render('User/Dashboard', $this->data);
        }
        else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        $this->data['smenu'] = 'profile';
        $this->data['user'] = Auth::user();
        return Inertia::render('User/Profile', $this->data);
    }

    public function orders(Request $request)
    {
        $this->data['smenu'] = 'orders';
        $user = auth()->user();
        $this->data['userinfo'] = $user;
        $this->data['orders'] =Transaction::select('id', 'payment_status', 'status', 'invoice_no', 'final_total', 'shipping_status')->with('sell_lines')->where('type', 'sell')->where('contact_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return Inertia::render('User/Orders', $this->data);
    }

    public function addresses(Request $request)
    {
        $this->data['smenu'] = 'addresses';
        $user = auth()->user();
        $this->data['userinfo'] = $user;
        $this->data['addresses'] = Address::where('user_id', $user->id)->get();
        return Inertia::render('User/Addresses', $this->data);
    }

    public function wishlist(Request $request)
    {
        $this->data['smenu'] = 'wishlist';
        $this->data['wishlists'] = Wishlist::with('product')->where('user_id', Auth::user()->id)->paginate(9);////8
        return Inertia::render('User/Wishlist', $this->data);
    }

    public function userProfileUpdate(Request $request)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        
        $user->avatar_original = $request->photo;

        $seller = $user->seller;

        if($seller){
            $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
            $seller->bank_payment_status = $request->bank_payment_status;
            $seller->bank_name = $request->bank_name;
            $seller->bank_acc_name = $request->bank_acc_name;
            $seller->bank_acc_no = $request->bank_acc_no;
            $seller->bank_routing_no = $request->bank_routing_no;

            $seller->save();
        }

        $user->save();

        return back();
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }
    
    public function orderDetail($oid = null)
    {
        $order = Transaction::with(['sell_lines', 'payment_lines'])->where('type', 'sell')->where('contact_id', Auth::user()->id)->where('invoice_no', base64_decode($oid))->first();
        if(!$order) {
            return redirect()->route('orders');
        }
        $this->data['smenu'] = 'orders';
        $user = auth()->user();
        $this->data['userinfo'] = $user;
        $this->data['order'] = $order;
        return Inertia::render('User/OrderDetail', $this->data);
    }
}