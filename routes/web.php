<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CustomerAdminController;

// 🏠 HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin Login
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ProductAdminController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductAdminController::class, 'listProducts'])->name('products.list');
    Route::resource('products', ProductAdminController::class)->except(['show', 'index']);
    Route::resource('orders', OrderAdminController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('customers', CustomerAdminController::class)->only(['index', 'show', 'destroy']);
    Route::get('reports', [ReportAdminController::class, 'index'])->name('reports.index');
    Route::get('settings', [\App\Http\Controllers\Admin\SettingAdminController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingAdminController::class, 'update'])->name('settings.update');
});

// Produk 🛍️
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');

// Profil
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Address Management
    Route::get('/profile/addresses/create', [ProfileController::class, 'createAddress'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::get('/profile/addresses/{address}/edit', [ProfileController::class, 'editAddress'])->name('profile.addresses.edit');
    Route::put('/profile/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::put('/profile/addresses/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.setDefault');
});

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