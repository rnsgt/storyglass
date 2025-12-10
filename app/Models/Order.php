<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'telepon',
        'alamat',
        'total',
        'status',
        'payment_method',
        'courier_name',
        'tracking_number',
        'shipped_at'
    ];
    
    protected $casts = [
        'shipped_at' => 'datetime',
    ];
    
    // Relasi
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    
    // Accessor untuk kompatibilitas dengan view
    public function getShippingAddressAttribute()
    {
        return $this->alamat;
    }
    
    public function getTotalPriceAttribute()
    {
        return $this->total;
    }
}