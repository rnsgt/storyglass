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
            'type' => 'nullable|string|in:single,cart',
            'product_id' => 'nullable|exists:products,id',
        ]);

        // Cek apakah checkout single product atau dari cart
        $isSingleProduct = $request->input('type') === 'single' && $request->filled('product_id');
        
        if ($isSingleProduct) {
            // Checkout single product (Beli Langsung)
            $product = Product::findOrFail($request->product_id);
            $total = $product->harga;
            
            // Simpan order
            $order = \App\Models\Order::create([
                'user_id' => Auth::id(),
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'alamat' => $validated['alamat'],
                'total' => $total,
                'paid' => false,
            ]);

            // Simpan order item
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'nama' => $product->nama,
                'harga' => $product->harga,
                'quantity' => 1,
                'subtotal' => $product->harga,
            ]);

            // Kurangi stok produk
            if ($product->stok > 0) {
                $product->decrement('stok', 1);
            }
        } else {
            // Checkout dari cart
            $data = $this->normalizeCartForView();
            
            // Simpan order
            $order = \App\Models\Order::create([
                'user_id' => Auth::id(),
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'alamat' => $validated['alamat'],
                'total' => $data['total'],
                'paid' => false,
            ]);

            // Simpan order items dan kurangi stok
            foreach ($data['cart'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'nama' => $item['nama'],
                    'harga' => $item['harga'],
                    'quantity' => $item['jumlah'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                ]);

                // Kurangi stok produk
                $product = Product::find($item['id']);
                if ($product && $product->stok >= $item['jumlah']) {
                    $product->decrement('stok', $item['jumlah']);
                }
            }

            session()->forget('cart');
        }

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