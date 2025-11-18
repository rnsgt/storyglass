<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []); // format: [productId => ['id'=>..., 'quantity'=>...], ...]

        // ambil semua product sekaligus untuk menghindari N+1
        $ids = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        // Bangun items konsisten sebagai collection objek { id, quantity, product }
        $items = collect($cart)->map(function ($entry, $key) use ($products) {
            $id = isset($entry['id']) ? $entry['id'] : $key;
            $quantity = (int) ($entry['quantity'] ?? $entry['jumlah'] ?? 1);
            $product = $products->get($id);
            return (object)[
                'id' => $id,
                'quantity' => $quantity,
                'product' => $product,
            ];
        })->filter(function($it) {
            return ! is_null($it->product); // buang item bila product tidak ditemukan
        });

        $total = $items->sum(function ($it) {
            return $it->product->harga * $it->quantity;
        });

        return view('cart.index', compact('items', 'total'));
    }

    public function add($id, Request $request)
    {
    $product = Product::findOrFail($id);
    $cart = session()->get('cart', []);
    $qty = (int) $request->input('quantity', $request->input('jumlah', 1));

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = ($cart[$id]['quantity'] ?? 0) + $qty;
    } else {
        $cart[$id] = [
            'id'       => $product->id,
            'quantity' => $qty,
            'nama'     => $product->nama,
            'harga'    => $product->harga,
            'image'    => $product->image ?? null,
        ];
    }

    session()->put('cart', $cart);
    // remove any dd() here
    return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
}

    public function update($id, Request $request)
    {
        $cart = session()->get('cart', []);
        if (! isset($cart[$id])) {
            return redirect()->route('cart.index');
        }

        $action = $request->input('action');
        if ($action === 'increase') {
            $cart[$id]['quantity'] = ($cart[$id]['quantity'] ?? 1) + 1;
        } elseif ($action === 'decrease') {
            $cart[$id]['quantity'] = max(1, ($cart[$id]['quantity'] ?? 1) - 1);
        } else {
            $qty = (int) $request->input('quantity', $cart[$id]['quantity'] ?? 1);
            $cart[$id]['quantity'] = max(1, $qty);
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_map(fn($i) => (int) ($i['quantity'] ?? $i['jumlah'] ?? 1), $cart));
        return response()->json(['count' => $count]);
    }
}