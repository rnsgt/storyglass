<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        ProductSeeder::class,  // 1. Jalankan Induk (Produk) dulu
        CartSeeder::class,     // 2. Jalankan Induk (Keranjang)
        CartItemSeeder::class, // 3. Baru jalankan Anak (Item Keranjang)
        ]);
    }

}
