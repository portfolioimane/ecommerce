<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PayPalController;
use App\Models\Product;

Route::get('/products', function() {
    $products = Product::all();
    return view('products', compact('products'));
});

Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::get('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');



Route::get('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/create-checkout-session', [CheckoutController::class, 'createCheckoutSession']);
Route::get('/success', [CheckoutController::class, 'success']);
Route::get('/cancel', [CheckoutController::class, 'cancel']);


Route::post('/paypal/create', [PayPalController::class, 'createPayment'])->name('paypal.create');
Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
