<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductAdminController;
use Illuminate\Support\Facades\Route;

// 🏠 HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/produk', [ProductAdminController::class,'index'])->name('produk.index');
//     Route::get('/produk/create', [ProductAdminController::class,'create'])->name('produk.create');
//     Route::post('/produk', [ProductAdminController::class,'store'])->name('produk.store');
//     Route::get('/produk/{id}/edit', [ProductAdminController::class,'edit'])->name('produk.edit');
//     Route::put('/produk/{id}', [ProductAdminController::class,'update'])->name('produk.update');
//     Route::delete('/produk/{id}', [ProductAdminController::class,'destroy'])->name('produk.destroy');
// });


// Produk 🛍️
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/cari', [ProductController::class, 'cari'])->name('produk.cari');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');
// Route::get('/produk/{id}', [ProductController::class, 'show'])->middleware('auth');
// Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');

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
//Dasboard
// Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

// Search
Route::get('/produk/cari', [ProductController::class, 'cari'])->name('produk.cari');


// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\CartController;
// use App\Http\Controllers\CheckoutController;

// // 🏠 HOME
// Route::get('/', [HomeController::class, 'index'])->name('home');

// // 🛍️ PRODUK
// Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
// Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');

// // 🛒 KERANJANG BELANJA
// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::post('/cart/add/{id}', [CartController::class, 'tambah'])->name('cart.add');
// Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
// Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route::get('/cart/clear', function () {
//     session()->forget('cart');
//     return redirect()->back()->with('success', 'Keranjang dikosongkan.');
// })->name('cart.clear');

// // 💳 CHECKOUT
// Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::get('/checkout/beli/{id}', [CheckoutController::class, 'beli'])->name('checkout.beli');
// Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');

// // 🔐 AUTH (LOGIN / REGISTER)
// Route::get('/auth/login', fn() => view('auth.login'))->name('login');
// Route::get('/auth/register', fn() => view('auth.register'))->name('register');
// 🔐 AUTH (LOGIN / REGISTER)
require __DIR__.'/auth.php';
