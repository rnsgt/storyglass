<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'nama' => 'Kacamata Elegan',
                'harga' => 250000,
                'stok' => 50,
                'deskripsi' => 'Kacamata elegan dengan desain minimalis cocok untuk gaya formal maupun santai.',
                'gambar' => 'produk1.jpg',
            ],
            [
                'nama' => 'Kacamata Retro',
                'harga' => 300000,
                'stok' => 30,
                'deskripsi' => 'Desain retro klasik dengan bingkai tebal yang ikonik.',
                'gambar' => 'produk2.jpg',
            ],
            [
                'nama' => 'Kacamata Modern',
                'harga' => 280000,
                'stok' => 45,
                'deskripsi' => 'Model modern dengan bahan ringan dan nyaman digunakan sepanjang hari.',
                'gambar' => 'produk3.jpg',
            ],
            [
                'nama' => 'Kacamata Stylish',
                'harga' => 310000,
                'stok' => 25,
                'deskripsi' => 'Tampilan stylish dengan sentuhan keemasan untuk tampil percaya diri.',
                'gambar' => 'produk4.jpg',
            ],
        ]);
    }
}
