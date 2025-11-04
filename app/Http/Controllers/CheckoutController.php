<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['jumlah']);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function beli($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $product = Product::findOrFail($id);
        return view('checkout.single', compact('product'));
    }

    public function proses(Request $request)
    {
        // implementasi penyimpanan transaksi
        session()->forget('cart');
        return redirect()->route('home')->with('success', 'Pembelian berhasil!');
    }
}
