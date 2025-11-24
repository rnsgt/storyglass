<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerAdminController extends Controller
{
    /**
     * Tampilkan daftar semua pelanggan (User non-admin).
     */
    public function index()
    {
        // Ambil user yang role-nya BUKAN admin
        // withCount('orders') akan menghitung jumlah pesanan user tersebut otomatis
        $customers = User::where('role', '!=', 'admin')
                         ->withCount('orders') 
                         ->latest()
                         ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Tampilkan detail pelanggan dan riwayat ordernya.
     */
    public function show($id)
    {
        $customer = User::where('role', '!=', 'admin')
                        ->with(['orders' => function($query) {
                            $query->latest(); // Urutkan order dari yang terbaru
                        }])
                        ->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Hapus pelanggan (Hati-hati, ini akan menghapus orderan dia juga jika cascade aktif).
     */
    public function destroy($id)
    {
        $customer = User::where('role', '!=', 'admin')->findOrFail($id);
        
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Data pelanggan berhasil dihapus, King.');
    }
}