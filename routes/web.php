<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;

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
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');
// Route::get('/produk/{id}', [ProductController::class, 'show'])->middleware('auth');
// Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');

// Profil


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

//Dasboard
//Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

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

Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot / request password reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Reset password form & submit
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Email verification prompt & verification
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm password
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update password (profile)
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// 🔹 Login routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// 🔹 Register routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// 🔹 Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
