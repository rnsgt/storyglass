<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderAdminController extends Controller
{
    /**
     * Tampilkan daftar pesanan masuk.
     */
    public function index()
    {
        // Ambil pesanan terbaru beserta data user dan item-nya
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail satu pesanan.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan (Misal: Dikirim / Selesai).
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Status pesanan berhasil diperbarui, King!');
    }
}