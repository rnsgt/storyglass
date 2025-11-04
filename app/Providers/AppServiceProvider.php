<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Komposisikan variabel $cartCount ke semua views yang menggunakan 'layouts.main'
        View::composer('layouts.main', function ($view) {
            $cartCount = 0;
            
            if (Auth::check()) {
                // Pengguna terautentikasi: hitung dari cart_items berdasarkan user_id
                $cartCount = Cart::where('user_id', Auth::id())
                                ->withCount('items') // Hitung jumlah items
                                ->first()?->items_count ?? 0;

            } else {
                // Pengguna Guest: hitung dari cart_items berdasarkan session_id
                $cartCount = Cart::where('session_id', session()->getId())
                                ->withCount('items')
                                ->first()?->items_count ?? 0;
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
