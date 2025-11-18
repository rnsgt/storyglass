<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class MergeCartAfterLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $sessionId = Session::getId();

        // Cart milik session (guest)
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart) {
            return;
        }

        // Cart milik user (login)
        $userCart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        // Pindahkan semua item dari session cart ke user cart
        foreach ($guestCart->items as $item) {
            $existing = CartItem::where('cart_id', $userCart->id)
                                ->where('product_id', $item->product_id)
                                ->first();

            if ($existing) {
                // Tambah quantity jika sudah ada
                $existing->quantity += $item->quantity;
                $existing->save();
            } else {
                // Buat item baru
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);
            }
        }

        // Hapus cart session setelah dipindah
        $guestCart->delete();
    }
}
