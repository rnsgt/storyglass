<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'King Admin',
            'email' => 'admin@gmail.com', // Email login
            'password' => Hash::make('admin123'), // Password login
            'role' => 'admin', // <--- INI KUNCINYA
        ]);
    }
}
