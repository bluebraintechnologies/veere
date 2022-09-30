<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Address;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Session;

class CheckoutController extends Controller
{
    protected $data = [];
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $carts = Cart::where('user_id', Auth::user()->id)->count();
        if ($carts > 0) {
            $this->data['isUserLogged'] = 0;
            if (Auth::check()) {
                $this->data['isUserLogged'] = 1;
            }
            $user = Auth::user();
            $this->data['step'] = 'shipping';
            $this->data['addresses'] = Address::where('user_id', $user->id)->get();
            $this->data['set_default'] = Address::where('user_id', $user->id)->where('set_default', 1)->select('id')->first();
            $this->data['set_default_billing'] = Address::where('user_id', $user->id)->where('set_default_billing', 1)->select('id')->first();
            return Inertia::render('Checkout/Shipping', $this->data);
        }
        return redirect()->route('dashboard');
    }

    public function checkout(Request $request)
    {
        if ($request->payment_option != null) {
            (new OrderController)->store($request);

            $request->session()->put('payment_type', 'cart_payment');
            
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {
                if ($request->payment_option == 'razorpay') {
                    $razorpay = new RazorpayController;
                    return $razorpay->payWithRazorpay($request);
                } elseif ($request->payment_option == 'cash_on_delivery') {
                    return redirect()->route('order_confirmed');
                } else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->save();
                    }
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            return redirect()->route('cart');
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        
        (new OrderController)->updatePaymentStatus($combined_order_id, $payment);

        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        $user = auth()->user();
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            $this->data['step'] = 'shipping';
            $this->data['addresses'] = Address::where('user_id', $user->id)->get();
            return Inertia::render('Checkout/Shipping', $this->data);
        }
        return redirect()->route('dashboard');
    }

    public function get_payment(Request $request)
    {
        $this->data['isUserLogged'] = 0;
        if (Auth::check()) {
            $this->data['isUserLogged'] = 1;
        }
        $user = auth()->user();
        $carts = Cart::where('user_id', Auth::user()->id)->count();
        if ($carts > 0) {
            $this->data['step'] = 'payment';
            $this->data['addresses'] = Address::where('user_id', $user->id)->get();
            return Inertia::render('Checkout/Payment', $this->data);
        }
        return redirect()->route('dashboard');
    }

    public function order_confirmed()
    {
        $combined_order = Transaction::findOrFail(Session::get('combined_order_id'));
        $this->data['isUserLogged'] = 0;
        if (Auth::check()) {
            $this->data['isUserLogged'] = 1;
        }
        Session::forget('combined_order_id');
        Cart::where('user_id',  $combined_order->contact_id)->delete();
        $this->data['message'] = '';
        return Inertia::render('Checkout/OrderConfirmed', $this->data);

    }
}
