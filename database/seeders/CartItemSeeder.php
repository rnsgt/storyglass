<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        CartItem::create([
            'cart_id' => 1,          // pastikan ID ini ada di tabel carts
            'product_id' => 1,       // pastikan product_id juga ada di tabel products
            'quantity' => 2,
        ]);

        CartItem::create([
            'cart_id' => 1,
            'product_id' => 2,
            'quantity' => 1,
        ]);
    }
}
