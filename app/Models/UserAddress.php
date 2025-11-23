<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format alamat lengkap
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->alamat,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
        ]);
        
        return implode(', ', $parts);
    }
}
