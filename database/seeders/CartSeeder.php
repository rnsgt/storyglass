<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        Cart::create([
            'session_id' => 'test-session-123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tambahkan beberapa contoh cart lain jika mau
        Cart::create([
            'session_id' => 'test-session-456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
