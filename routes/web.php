<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 🏠 HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

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
//Dasboard
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

<<<<<<< HEAD
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ProductAdminController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductAdminController::class); 
=======
Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot');

Route::post('/chatbot/send', function (Request $request) {

    $msg = strtolower($request->message);

    if (str_contains($msg, 'bulat')) {
        return response()->json([
            'reply' => 'Oke! Berikut rekomendasi kacamata untuk wajah bulat.',
            'product' => [
                'name' => 'Kacamata Aviator',
                'price' => '149.000',
                'image' => '/images/aviator.jpg',
            ]
        ]);
    }

    return response()->json([
        'reply' => 'Maaf, saya belum mengerti. Coba ketik: "kacamata untuk wajah bulat".'
    ]);
>>>>>>> 686109c09583ba431c14e20514adeac510e69424
});

// 🔐 AUTH (LOGIN / REGISTER)
require __DIR__.'/auth.php';