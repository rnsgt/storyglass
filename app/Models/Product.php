<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
    {
        protected $table = 'products';

        protected $fillable = [
            'nama',
            'harga',
            'deskripsi',
            'gambar',
        ];
        public function cartItems()
        {
            return $this->hasMany(CartItem::class);
        }

    }
