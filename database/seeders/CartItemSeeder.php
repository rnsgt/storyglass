<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil cart pertama sebagai contoh
        $cart = Cart::first();
        
        // Ambil beberapa produk untuk dijadikan dummy item
        $products = Product::take(4)->get();

        // Pastikan ada cart dan produk sebelum insert
        if ($cart && $products->count() > 0) {
            foreach ($products as $product) {
                CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'quantity'   => rand(1, 3), // Random quantity 1-3
                    'price'      => $product->harga, // <--- TAMBAHKAN INI: Ambil harga dari produk
                ]);
            }
        }
    }
}
