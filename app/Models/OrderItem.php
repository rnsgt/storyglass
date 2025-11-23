<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','nama','harga','quantity','subtotal'];
    
    // Relasi
    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    
    // Accessor untuk kompatibilitas dengan view
    public function getPriceAttribute()
    {
        return $this->harga;
    }
}