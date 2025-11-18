<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Tambahkan kolom 'price' dengan tipe decimal, bisa disesuaikan
            // Setelah koma di allow 2 angka (contoh: 15000.50)
            $table->decimal('price', 15, 2)->after('quantity'); 
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
