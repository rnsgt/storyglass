<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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

        User::create([
            'name' => 'Regular User',
            'email' => 'user@gmail.com', // Email login
            'password' => Hash::make('user123'), // Password login
            'role' => 'user', // <--- INI KUNCINYA
        ]);
    }
}
