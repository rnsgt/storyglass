<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingAdminController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('key')->get()->groupBy('type');
        
        // Debug: pastikan data ada
        if ($settings->isEmpty()) {
            // Jika kosong, coba run seeder
            \Artisan::call('db:seed', ['--class' => 'SettingSeeder']);
            $settings = Setting::orderBy('key')->get()->groupBy('type');
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ]);

        // Get all settings
        $allSettings = Setting::all();
        
        foreach ($allSettings as $setting) {
            // Handle checkbox (boolean type)
            if ($setting->type === 'boolean') {
                $value = isset($validated['settings'][$setting->key]) ? '1' : '0';
            } else {
                $value = $validated['settings'][$setting->key] ?? $setting->value;
            }
            
            $setting->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
