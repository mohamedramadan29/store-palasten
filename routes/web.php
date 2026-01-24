<?php

use App\Http\Controllers\front\FrontController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\front\ShopController;
use \App\Http\Controllers\front\ShopCollection;
use \App\Http\Controllers\front\ProductController;
use \App\Http\Controllers\front\CartController;
use \App\Http\Controllers\front\CheckoutController;
use \App\Http\Controllers\front\WishlistController;
use \App\Http\Controllers\front\OrderController;
use \App\Http\Controllers\front\TermsController;
use \App\Http\Controllers\front\OfferController;
use \App\Http\Controllers\front\OfferOrderController;

Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/get-product-details/{id}', 'getProductDetails');
    Route::get('faq','faq');

});

Route::controller(ShopController::class)->group(function () {
    Route::get('shop', 'shop');
});

Route::controller(ShopCollection::class)->group(function () {
    Route::get('collection', 'collection');
    Route::get('collection/{slug}', 'collection_details');
    Route::get('collection/{slug}/{sub_category_slug}', 'SubCollectionDetails');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('product/{slug}', 'product');
    Route::get('search-products', 'search')->name('search.products');
    Route::post('/product/{id}/get-price', 'getPrice')->name('product.getPrice');
    Route::get('/product/quick-view/{id}', 'quickView')->name('product.quick-view');
    Route::get('main-search','main_search');

});

Route::controller(CartController::class)->group(function () {
    Route::get('cart', 'cart');
    Route::post('cart/add', 'add');
    Route::get('/cart/items', 'getCartItems');
    Route::post('cart/delete/{id}', 'delete');
    Route::post('/cart/update', 'updateCart')->name('cart.update');
    Route::post('apply_coupon', 'apply_coupon');

});

Route::controller(CheckoutController::class)->group(function () {
    Route::get('checkout', 'checkout');
    Route::get('/get-shipping-price', 'getShippingPrice');

});

Route::controller(WishlistController::class)->group(function () {
    Route::get('wishlist', 'index');
    Route::post('wishlist/store', 'store');
    Route::post('wishlist/delete/{id}', 'delete');
});
Route::controller(TermsController::class)->group(function () {
    Route::get('terms', 'index');
});
Route::controller(OrderController::class)->group(function () {
    Route::post('order/store', 'store');
    Route::get('thanks', 'thanks');
});

Route::controller(OfferController::class)->group(function () {
    Route::get('offer/{slug}', 'offer');
});

Route::controller(OfferOrderController::class)->group(function () {
    Route::post('offer_order/store/{id}', 'store_offer');
});

Route::controller(\App\Http\Controllers\front\ReviewController::class)->group(function (){
   Route::match(['post','get'],'review','review');
});
@include 'admin.php';
