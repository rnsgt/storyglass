<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Gunakan Bootstrap untuk pagination
        Paginator::useBootstrapFive();
        
        View::composer('layouts.main', function ($view) {
            // hitung dari session (guest)
            $sessionCart = session()->get('cart', []);
            $sessionCount = array_sum(array_map(fn($i) => (int) ($i['quantity'] ?? $i['jumlah'] ?? 1), $sessionCart));

            if (Auth::check()) {
                // hitung dari DB untuk user terautentikasi (pastikan relasi items() ada)
                $dbCount = Cart::where('user_id', Auth::id())
                                ->withCount('items')
                                ->first()?->items_count ?? 0;

                // Pilih salah satu: gunakan DB count atau gabungkan session+DB.
                $cartCount = $dbCount;
            } else {
                $cartCount = $sessionCount;
            }

            $view->with('cartCount', $cartCount);
        });
    }
}