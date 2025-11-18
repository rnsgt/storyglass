<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product; // Import Model Product
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
            // Ambil data produk asli untuk mendapatkan harga terkini
            $product = Product::find($item->product_id);
            
            // Jika produk sudah dihapus dari database, lewati item ini
            if (!$product) {
                continue;
            }

            $existing = CartItem::where('cart_id', $userCart->id)
                                ->where('product_id', $item->product_id)
                                ->first();

            if ($existing) {
                // Tambah quantity jika sudah ada
                $existing->quantity += $item->quantity;
                // Opsional: Update harga ke harga terbaru jika diperlukan
                // $existing->price = $product->harga; 
                $existing->save();
            } else {
                // Buat item baru DENGAN PRICE
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $product->harga, // <-- INI YANG KURANG SEBELUMNYA
                ]);
            }
        }

        // Hapus cart session setelah dipindah
        $guestCart->delete();
    }
}