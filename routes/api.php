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
Route::post('/set-as-delivery-address', [App\Http\Controllers\API\AddressController::class, 'defaultDeliveryAddress']);
Route::post('/set-as-billing-address', [App\Http\Controllers\API\AddressController::class, 'defaultBillingAddress']);
Route::post('/password-check', [App\Http\Controllers\API\HomeController::class, 'passwordCheck']);
//product
Route::post('/get-selected-category-product', [App\Http\Controllers\API\HomeController::class, 'getSelectedCategoryProduct']);
Route::post('/submit-product-review', [App\Http\Controllers\API\HomeController::class, 'submitProductReview']);
Route::post('/get-postal-code-delivery-status', [App\Http\Controllers\API\HomeController::class, 'postalCodeDeliveryStatus']);

// cart
Route::resource('cart', App\Http\Controllers\API\CartController::class);
Route::post('cart/update-address', [App\Http\Controllers\API\CartController::class, 'updateAddress']);
Route::post('cart/update-billing-address', [App\Http\Controllers\API\CartController::class, 'updateBillingAddress']);
Route::apiResource('address', App\Http\Controllers\API\AddressController::class);
Route::post('cart/update-delivery-timing', [App\Http\Controllers\API\CartController::class, 'updateDeliveryTiming']);

//checkout
Route::post('set-default-shipping-billing-address', [App\Http\Controllers\API\AddressController::class, 'setDefaultShippingBillingAddress']);
Route::post('un-set-default-shipping-billing-address', [App\Http\Controllers\API\AddressController::class, 'unsetDefaultShippingBillingAddress']);
//wishlist
Route::post('delete-wishlist-item', [App\Http\Controllers\API\WishlistController::class, 'deleteWishlistItem']);
// new

Route::post('apply_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'apply_coupon_code']);
Route::post('remove_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'remove_coupon_code']);
Route::resource('wishlist', App\Http\Controllers\API\WishlistController::class);