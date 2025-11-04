<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@Index');

//countdown
Route::get('/countdown', 'HomeController@Countdown');


//Menu Brand
Route::post('/menu-brand/{brand_id}', 'BrandController@MenuShowBrand');
Route::get('/shop-now', 'HomeController@MenuShowProductNow');
Route::get('/product-category/{product_type_id}', 'HomeController@MenuShowProductType');
Route::get('/product-brand/{product_brand_id}', 'HomeController@MenuShowProducBrand');
Route::get('/product-collection/{product_collection_id}', 'HomeController@MenuShowProductCollection');
Route::get('/product-detail/{product_id}', 'HomeController@ProductDetail');
Route::get('/promotion', 'HomeController@MenuShowProductDiscount');
Route::get('/product-discount-detail/{product_id}', 'HomeController@MenuShowProductDiscountDetail');
//Order Tracking
Route::get('/order-tracking', 'HomeController@OrderTracking');
Route::post('/get-order-tracking', 'HomeController@GetRequestOrderTracking');
Route::get('/show-order-tracking', 'HomeController@ShowOrderTracking');
Route::get('/show-order-tracking-detai/{order_id}', 'HomeController@ShowOrderTrackingDetail');

Route::get('/sendmail', 'HomeController@sendmail');

//Add cart
Route::post('/add-cart', 'CartController@AddToCart');
Route::get('/cart', 'CartController@ShowCart');
Route::get('/delete-cart', 'CartController@DeleteCartRow');
Route::post('/update-cart', 'CartController@UpdateCart');
Route::post('/check-coupon', 'CartController@CheckCoupon');
Route::get('/delete-mini-cart/{session_id}', 'CartController@DeleteMiniCart');

//Checkout
Route::get('/checkout', 'CheckoutController@Index');
Route::post('/select-transport-fee-home', 'CheckoutController@SelectTransportFeeHome');
Route::post('/check-transport-feeship', 'CheckoutController@CheckTransportFee');
Route::post('/order-checkout-save', 'CheckoutController@OrderCheckoutSave');
Route::post('/select-address', 'CheckoutController@SelectAddress');
Route::get('/delete-coupon-cart', 'CheckoutController@DeleteCoupon');
Route::get('/delete-transport-fee-cart', 'CheckoutController@DeleteFeeship');

//Account
Route::get('/login-customer', 'CustomerController@ShowLogin');
Route::get('/show-verification-email-customer', 'CustomerController@ShowVerificationEmail');
Route::post('/verification-email-customer', 'CustomerController@VerificationEmailCustomer');
Route::get('/register-customer', 'CustomerController@ShowRegister');
Route::post('/register-customer-save', 'CustomerController@RegisterCustomer');
Route::post('/check-login-customer', 'CustomerController@CheckLoginCustomer');
Route::get('/my-account', 'CustomerController@ShowMyAccount');
Route::post('/customer-edit-save/{customer_id}', 'CustomerController@CustomerEditSave');
Route::get('/logout-customer', 'CustomerController@LogoutCustomer');

Route::get('/customer-show-order/{order_id}', 'CustomerController@ShowCustomerOrderDetail');
Route::get('/customer-cancel-order/{order_id}', 'CustomerController@CustomerCancelOrder');

Route::get('/show-verification-password-customer', 'CustomerController@ShowVerificationResetPassword');
Route::post('/verification-password-customer', 'CustomerController@VerificationResetPasswordCustomer');
Route::get('/reset-password-customer', 'CustomerController@ShowResetPassword');
Route::post('/reset-password-customer-save', 'CustomerController@ResetPasswordCustomer');

Route::post('/customer-order-delivery-update-save/{order_id}', 'CustomerController@CustomerUpdateAddressDelivery');
Route::post('/customer-change-password-save/{customer_email}', 'CustomerController@ChangePasswordCustomer');

//Comment Review
Route::post('/post-comment-customer', 'CommentController@PostCommentCustomer');
Route::post('/load-comment','CommentController@LoadComment');

//Search Customer
Route::get('/search-product-customer', 'SearchController@ShowProductSearchHeaderCustomer');
Route::get('/search-product-filter-customer', 'SearchController@ShowProductSearchFilterCustomer');


//About US
Route::get('/about-us', 'AboutStoreController@ShowAboutUS');

//Wishlist
Route::get('/my-wishlists', 'HomeController@ShowMyWishlist');
Route::post('/show-wishlist', 'HomeController@ShowAllWishlist');

//Viewed
Route::get('/delete-mini-product-viewed/{product_id}', 'HomeController@DeleteMiniProductViewed');