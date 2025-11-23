<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CheckoutController extends Controller
{
    // Gunakan normalisasi sama seperti CartController
    protected function normalizeCartForView(): array
    {
        $cart = session()->get('cart', []);
        $ids = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        $items = collect($cart)->map(function ($entry, $key) use ($products) {
            $id = isset($entry['id']) ? $entry['id'] : $key;
            $quantity = (int) ($entry['quantity'] ?? $entry['jumlah'] ?? 1);
            $product = $products->get($id);
            if (! $product) return null;
            return [
                'id' => $id,
                'nama' => $product->nama,
                'harga' => $product->harga,
                'jumlah' => $quantity,
                'image' => $product->image ?? $product->gambar ?? null,
            ];
        })->filter()->values();

        $total = $items->sum(fn($it) => $it['harga'] * $it['jumlah']);

        return [
            'cart' => $items->toArray(),
            'total' => $total,
        ];
    }

    public function index()
    {
        $data = $this->normalizeCartForView();
        return view('checkout.checkout', array_merge($data, ['product' => null]));
    }

    public function beli($id)
    {
        $product = Product::findOrFail($id);
        $data = $this->normalizeCartForView();
        return view('checkout.checkout', array_merge($data, ['product' => $product]));
    }

    public function proses(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk menyelesaikan pembayaran.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        // Simpan order
        $data = $this->normalizeCartForView();
        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'],
            'total' => $data['total'],
            'paid' => false,
        ]);

        session()->forget('cart');

        // redirect ke halaman pembayaran QRIS
        return redirect()->route('checkout.qris', ['order' => $order->id]);
    }
    public function qris($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId); // sesuaikan model
        // generate / ambil URL gambar QRIS dari gateway atau buat QR lokal
        $qrcodeUrl = asset('image/qris1.jpeg'); // contoh
        return view('checkout.qris', compact('order','qrcodeUrl'));
    }

    public function status($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        // check status pembayaran dari DB / payment gateway
        return response()->json(['paid' => $order->paid ?? false]);
    }
    public function success()
    {
        return view('checkout.success');
    }
}