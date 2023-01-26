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
Route::post('/updateNew/{id}', [App\Http\Controllers\API\CartController::class, 'updateNew']);
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
Route::get('/get-stock-details', [App\Http\Controllers\API\HomeController::class, 'checkStock']);
// cart
Route::resource('cart', App\Http\Controllers\API\CartController::class);
Route::post('cart/update-address', [App\Http\Controllers\API\CartController::class, 'updateAddress']);
Route::post('cart/update-billing-address', [App\Http\Controllers\API\CartController::class, 'updateBillingAddress']);
Route::apiResource('address', App\Http\Controllers\API\AddressController::class);
Route::post('cart/update-delivery-timing', [App\Http\Controllers\API\CartController::class, 'updateDeliveryTiming']);

//checkout
Route::post('set-default-shipping-billing-address', [App\Http\Controllers\API\AddressController::class, 'setDefaultShippingBillingAddress']);

Route::post('set-default-shipping-address', [App\Http\Controllers\API\AddressController::class, 'setDefaultShippingAddress']);
Route::post('set-default-billing-address', [App\Http\Controllers\API\AddressController::class, 'setDefaultBillingAddress']);

Route::post('un-set-default-shipping-billing-address', [App\Http\Controllers\API\AddressController::class, 'unsetDefaultShippingBillingAddress']);
//wishlist
Route::post('delete-wishlist-item', [App\Http\Controllers\API\WishlistController::class, 'deleteWishlistItem']);
// new

Route::post('apply_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'apply_coupon_code']);
Route::post('remove_coupon_code', [App\Http\Controllers\API\CheckoutController::class, 'remove_coupon_code']);
Route::resource('wishlist', App\Http\Controllers\API\WishlistController::class);

//deals
Route::get('/get-deal-items', [App\Http\Controllers\API\HomeController::class, 'getDealItems']);
Route::get('/get-today-deals', [App\Http\Controllers\API\HomeController::class, 'getTodayDeal']);

//order
Route::post('/customer-order-cancel', [App\Http\Controllers\UserController::class, 'customerOrderCancel']);
Route::post('/customer-order-return', [App\Http\Controllers\UserController::class, 'customerOrderReturn']);

//reward point
Route::get('/get-reward-point-setting', [App\Http\Controllers\API\HomeController::class, 'getRewardPointSetting']);
Route::post('/pay-with-reward-points', [App\Http\Controllers\API\CartController::class, 'payWithRewardPoints']);

Route::post('/is-account-verified', [App\Http\Controllers\HomeController::class, 'isAccountVerified']);
Route::post('/check-cartI-tem-deliveribility', [App\Http\Controllers\API\HomeController::class, 'checkCartItemDeliveribility']);
Route::post('/remove-shopping-address', [App\Http\Controllers\API\HomeController::class, 'removeShoppingAddress']);
Route::post('/get-otp', [App\Http\Controllers\API\HomeController::class, 'getOTP']);
Route::post('/login-otp', [App\Http\Controllers\API\HomeController::class, 'loginOtp']);
Route::post('/check-login', [App\Http\Controllers\API\HomeController::class, 'checkLogin']);

Route::post('/register-customer', [App\Http\Controllers\API\HomeController::class, 'registerCustomer']);
Route::post('/check-email', [App\Http\Controllers\API\HomeController::class, 'checkEmail']);
Route::post('/generate-forgot-password-otp', [App\Http\Controllers\API\HomeController::class, 'generateForgotPasswordOTP']);
Route::post('/change-password-customer', [App\Http\Controllers\API\HomeController::class, 'changePasswordCustomer']);
Route::post('/check-mobile-exist', [App\Http\Controllers\API\HomeController::class, 'checkMobileExist']);
Route::post('/generate-cod-otp', [App\Http\Controllers\API\HomeController::class, 'generateCodOtp']);
Route::post('/check-cod-otp', [App\Http\Controllers\API\HomeController::class, 'checkCodOtp']);
Route::get('/get-all-offers', [App\Http\Controllers\API\OffersController::class, 'index']);
Route::post('/get-offer-details', [App\Http\Controllers\API\OffersController::class, 'details']);
Route::post('/save-enquiry', [App\Http\Controllers\PagesController::class, 'saveEnquiry']);
Route::get('/get-business-detail', [App\Http\Controllers\PagesController::class, 'business']);
Route::post('/get-product-list', [App\Http\Controllers\UserController::class, 'getProducts']);
Route::get('/preferred-delivery-time', [App\Http\Controllers\UserController::class, 'preferred_delivery_time']);
Route::get('/get-user-reward-points-earned', [App\Http\Controllers\UserController::class, 'getUserRewardPointsEarned']);
Route::get('/get-user-reward-points-ontheway', [App\Http\Controllers\UserController::class, 'getUserRewardPointsOnthewar']);
Route::post('/submit-order-review', [App\Http\Controllers\API\HomeController::class, 'submitOrderReview']);
Route::post('/add-shipping-address-to-cart-item/{address_id}', [App\Http\Controllers\API\HomeController::class, 'addShippingAddressToCartItem']);
Route::post('/add-billing-address-to-cart-item/{address_id}', [App\Http\Controllers\API\HomeController::class, 'addBillingAddressToCartItem']);
Route::post('/check-pincode-in-system', [App\Http\Controllers\API\HomeController::class, 'checkPincodeInSystem']);
Route::post('/get-featured-products/{location_id}', [App\Http\Controllers\API\HomeController::class, 'getFeaturedProducts']);
Route::post('/get-best-seller-product-mul-location/{location_id}', [App\Http\Controllers\API\HomeController::class, 'getBestSellerProductMulLocation']);
Route::post('/get-top-selling-product-mul-location/{location_id}', [App\Http\Controllers\API\HomeController::class, 'getBestTopSellingMulLocation']);
Route::post('/get-top-rated-product-mul-location/{location_id}', [App\Http\Controllers\API\HomeController::class, 'getBestTopRatedMulLocation']);
Route::post('/get-new-arrival-product-mul-location/{location_id}', [App\Http\Controllers\API\HomeController::class, 'getBestNewProductMulLocation']);
Route::get('/get-search-products', [App\Http\Controllers\API\HomeController::class, 'getSearchProductMulLocation']);
Route::get('/get-grocery-list-products', [App\Http\Controllers\API\HomeController::class, 'getGroceryListProducts']);
Route::get('/get-sliders-mul-location', [App\Http\Controllers\API\HomeController::class, 'getSlidersMulLocation']);
Route::get('/get-top-banner-mul-location', [App\Http\Controllers\API\HomeController::class, 'getTopBannerMulLocation']);
Route::get('/get-bottom-banner-mul-location', [App\Http\Controllers\API\HomeController::class, 'getBottomBannerMulLocation']);
Route::get('/get-home-offers-mul-location', [App\Http\Controllers\API\HomeController::class, 'getHomeOffersMulLocation']);
Route::get('/get-offers-mul-location', [App\Http\Controllers\API\HomeController::class, 'getOffersMulLocation']);
Route::get('/get-street-address-from', [App\Http\Controllers\API\HomeController::class, 'getStreetAddressFrom']);
Route::get('/check-cart-item-location', [App\Http\Controllers\API\CartController::class, 'checkCartItemLocation']);
Route::get('/delete-other-location-items', [App\Http\Controllers\API\CartController::class, 'deleteOtherLocationItems']);
Route::post('/is-account-verified', [App\Http\Controllers\HomeController::class, 'isAccountVerified']);
Route::post('/check-cartI-tem-deliveribility-final', [App\Http\Controllers\API\HomeController::class, 'checkCartItemDeliveribilityFinal']);
Route::get('/store-address-in-local-storage', [App\Http\Controllers\API\HomeController::class, 'storeAddressInLocalStorage']);


