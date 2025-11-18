<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class MergeGuestCart
{
    public function handle(Login $event)
    {
        $sessionId = Session::getId();
        $user = $event->user;

        // Cart guest berdasarkan session
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart) {
            return;
        }

        // Ambil atau buat cart untuk user
        $userCart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Pindahkan item dari cart guest ke cart milik user
        foreach ($guestCart->items as $item) {

            // Cek jika barang sudah ada sebelumnya di cart user
            $existing = $userCart->items()
                ->where('product_id', $item->product_id)
                ->first();

            if ($existing) {
                $existing->quantity += $item->quantity;
                $existing->save();
            } else {
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);
            }
        }

        // Hapus cart guest
        $guestCart->delete();
    }
}
