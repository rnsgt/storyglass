<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\ProductAdminController;

// 🏠 HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ProductAdminController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductAdminController::class, 'listProducts'])->name('products.list');
    Route::resource('products', ProductAdminController::class)->except(['show', 'index']);
    Route::resource('orders', OrderAdminController::class)->only(['index', 'show', 'update']);
});

// Produk 🛍️
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');

// Profil
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');

// Keranjang 🛒
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout 💳
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/beli/{id}', [CheckoutController::class, 'beli'])->name('checkout.beli');
Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/checkout/qris/{order}', [CheckoutController::class, 'qris'])->name('checkout.qris');
Route::get('/checkout/status/{order}', [CheckoutController::class, 'status'])->name('checkout.status');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Orders
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Chatbot AI
Route::post('/chatbot/send', [ChatbotController::class, 'send']);

Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot');


// 🔐 AUTH (LOGIN / REGISTER)
require __DIR__.'/auth.php';