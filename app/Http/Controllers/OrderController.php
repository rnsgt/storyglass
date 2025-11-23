<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik user yang sedang login.
     */
    public function index()
    {
        // Ambil order PUNYA USER SENDIRI (where user_id = Auth::id())
        $orders = Order::where('user_id', Auth::id())
                        ->with('items.product') // Eager load biar ringan
                        ->latest()
                        ->paginate(5);

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan spesifik.
     */
    public function show($id)
    {
        // Pastikan order ini milik user yang login (Security Check)
        $order = Order::where('user_id', Auth::id())
                      ->where('id', $id)
                      ->with('items.product')
                      ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}