<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'shop_name', 'value' => 'StoryGlass', 'type' => 'text'],
            ['key' => 'shop_tagline', 'value' => 'Cerita dalam Setiap Bingkai', 'type' => 'text'],
            ['key' => 'shop_address', 'value' => 'Jl. Sudirman No.1, Jakarta', 'type' => 'text'],
            ['key' => 'shop_phone', 'value' => '021-12345678', 'type' => 'text'],
            ['key' => 'shop_email', 'value' => 'info@storyglass.com', 'type' => 'text'],
            ['key' => 'shop_whatsapp', 'value' => '6281234567890', 'type' => 'text'],
            ['key' => 'shop_description', 'value' => 'Toko online terpercaya untuk berbagai produk berkualitas', 'type' => 'text'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'min_order', 'value' => '10000', 'type' => 'text'],
            ['key' => 'shipping_cost', 'value' => '10000', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
