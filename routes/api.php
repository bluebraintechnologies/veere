<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-category-list', [App\Http\Controllers\API\BlueBrainController::class, 'getNavigations']);
Route::get('/get-new-products', [App\Http\Controllers\API\HomeController::class, 'getNewProduct']);
Route::get('/get-deals-of-day', [App\Http\Controllers\API\HomeController::class, 'getDealsOfDay']);
Route::get('/get-main-slider-products', [App\Http\Controllers\API\HomeController::class, 'getMainSliderProducts']);
Route::get('/get-best-seller-products-all', [App\Http\Controllers\API\HomeController::class, 'getBestSellerProducts']);
Route::get('/get-new-arrivals', [App\Http\Controllers\API\HomeController::class, 'getNewArrivals']);
Route::get('/get-top-sellings', [App\Http\Controllers\API\HomeController::class, 'getTopSellings']);
Route::get('/get-top-rated', [App\Http\Controllers\API\HomeController::class, 'getTopRated']);
Route::get('/get-all-products', [App\Http\Controllers\API\HomeController::class, 'getAllProducts']);
Route::get('/get-all-categories', [App\Http\Controllers\API\HomeController::class, 'getCategories']);
Route::get('/get-auth-user', [App\Http\Controllers\HomeController::class, 'getAuthUser']);
Route::get('/get-all-product-testing', [App\Http\Controllers\API\HomeController::class, 'getAllProductTesting']);
Route::get('/get-wishlist-items', [App\Http\Controllers\API\HomeController::class, 'getWishlistItems']);
Route::post('/add-product-to-wishlist', [App\Http\Controllers\API\HomeController::class, 'addProductToWishlist']);
Route::post('/remove-product-from-wishlist', [App\Http\Controllers\API\HomeController::class, 'removeProductFromWishlist']);

Route::post('/password-check', [App\Http\Controllers\API\HomeController::class, 'passwordCheck']);
// cart
Route::resource('cart', App\Http\Controllers\API\CartController::class);
Route::post('cart/update-address', [App\Http\Controllers\API\CartController::class, 'updateAddress']);
Route::apiResource('address', App\Http\Controllers\API\AddressController::class);


// new

Route::post('apply_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'apply_coupon_code']);
Route::post('remove_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'remove_coupon_code']);
Route::resource('wishlist', App\Http\Controllers\API\WishlistController::class);