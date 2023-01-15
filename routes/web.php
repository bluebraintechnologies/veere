<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [App\Http\Controllers\HomeController::class, 'category'])->name('category');
Route::get('/product/{slug}', [App\Http\Controllers\HomeController::class, 'product'])->name('product');

Route::get('/offers', [App\Http\Controllers\HomeController::class, 'offers'])->name('offers');
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/grocery-list', [App\Http\Controllers\HomeController::class, 'groceryListFront'])->name('grocery-list-front');
});
Route::get('/offer/{id}', [App\Http\Controllers\HomeController::class, 'offersDetails'])->name('offer-detail');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
//Route::get('/login', [App\Http\Controllers\HomeController::class, 'login'])->name('login');

Route::get('cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::get('checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');

require __DIR__.'/auth.php';

Route::group(['prefix' => 'checkout', 'middleware' => ['auth','verified']], function() {
   // Route::get('/', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::any('/delivery_info', [App\Http\Controllers\CheckoutController::class, 'store_shipping_info'])->name('checkout.store_shipping_infostore');
    Route::post('/payment_select', [App\Http\Controllers\CheckoutController::class, 'store_delivery_info'])->name('checkout.store_delivery_info');

    Route::get('/order-confirmed', [App\Http\Controllers\CheckoutController::class, 'order_confirmed'])->name('order_confirmed');
    Route::get('/payment-select', [App\Http\Controllers\CheckoutController::class, 'get_payment_info'])->name('checkout.payment_info');
    
    Route::get('/payment', [App\Http\Controllers\CheckoutController::class, 'get_payment'])->name('checkout.get_payment');
    

    Route::post('/final-checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('payment.checkout');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth', 'verified']], function() {
    Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::get('/refer-and-earn', [App\Http\Controllers\UserController::class, 'referEarn'])->name('refer-earn');
    Route::get('/wishlist', [App\Http\Controllers\UserController::class, 'wishlist'])->name('wishlist');
    Route::get('/grocery-list', [App\Http\Controllers\UserController::class, 'grocerylist'])->name('grocerylist');
    Route::get('/reward-points', [App\Http\Controllers\UserController::class, 'rewardpoints'])->name('reward-points');
    Route::get('/reward-points-used', [App\Http\Controllers\UserController::class, 'rewardpointsUsed'])->name('reward-points-used');

    
    Route::get('/orders', [App\Http\Controllers\UserController::class, 'orders'])->name('orders');
    Route::get('/addresses', [App\Http\Controllers\UserController::class, 'addresses'])->name('addresses');

    Route::post('/new-user-verification', [App\Http\Controllers\HomeController::class, 'new_verify'])->name('user.new.verify');
    Route::post('/new-user-email', [App\Http\Controllers\HomeController::class, 'update_email'])->name('user.change.email');
    Route::post('/update-profile', [App\Http\Controllers\HomeController::class, 'userProfileUpdate'])->name('user.profile.update');
    Route::get('/order-detail/{id}', [App\Http\Controllers\UserController::class, 'orderDetail'])->name('order_detail');
    Route::get('/track-order/{id}', [App\Http\Controllers\UserController::class, 'trackOrder'])->name('track-order');
    Route::post('/logout-user', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');
    // Route::get('/feedback/{order_id}', [App\Http\Controllers\UserController::class, 'feedback']);
});
Route::get('/send/email', [App\Http\Controllers\HomeController::class, 'email'])->name('email'); // testing purpose

Route::post('rozer/payment/pay-success', [App\Http\Controllers\RazorpayController::class, 'payment'])->name('payment.rozer');
Route::get('/loginRedirect', [App\Http\Controllers\API\HomeController::class, 'loginRedirect'])->name('loginRedirect');

Route::get('/about-us', [App\Http\Controllers\PagesController::class, 'about'])->name('about');
Route::get('/terms-and-condistions', [App\Http\Controllers\PagesController::class, 'terms'])->name('terms-and-condistions');
Route::get('/privacy-policy', [App\Http\Controllers\PagesController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/return-policy', [App\Http\Controllers\PagesController::class, 'returnPolicy'])->name('return-policy');
Route::get('/faqs', [App\Http\Controllers\PagesController::class, 'faqs'])->name('faqs');
Route::get('/contact-us', [App\Http\Controllers\PagesController::class, 'contactus'])->name('contact-us');

Route::get('/agent-info', [App\Http\Controllers\OrderController::class, 'agentinfo']);
Route::get('/review/{order_id}', [App\Http\Controllers\HomeController::class, 'feedback']);
Route::get('/get-random-sting', [App\Http\Controllers\OtherController::class, 'generateRandomString']);