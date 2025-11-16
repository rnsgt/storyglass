<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    public function index()
    {
        // 1. Tentukan Kriteria Pencarian Keranjang
        if (Auth::check()) {
            $criteria = ['user_id' => Auth::id()];
        } else {
            // Gunakan session()->getId() dan pastikan kita memiliki kolom session_id di tabel carts
            $criteria = ['session_id' => Session::getId()];
        }

        // 2. Cari atau Buat Keranjang dengan eager loading items dan product
        $cart = Cart::with('items.product')->firstOrCreate($criteria);
        
        // 3. Pastikan items selalu berupa Collection, walaupun $cart baru dibuat
        // Karena firstOrCreate selalu mengembalikan instance Cart, items seharusnya ada.
        $items = $cart->items ?? new Collection();

        // 4. Hitung Total (gunakan metode koleksi yang lebih bersih)
        $total = $items->sum(function ($item) {
            // Pastikan product ada sebelum mengakses harga
            return $item->product ? ($item->product->harga * $item->quantity) : 0;
        });

        return view('cart.index', compact('items', 'total'));
    }


    public function add(Request $request, $id)
    {
        // Ambil produk berdasarkan id
        $product = Product::findOrFail($id);

        // Ambil cart berdasarkan user login atau session
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::check() ? Auth::id() : null,
            'session_id' => Session::getId(),
        ]);

        // Cari item apakah sudah ada di cart
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            // Jika sudah ada → tambah quantity
            $item->update([
                'quantity' => $item->quantity + 1,
            ]);
        } else {
            // Jika belum ada → buat cart item baru
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->harga, // Simpan harga produk
            ]);
        }

        // Hitung ulang total barang
        $cartCount = $cart->items()->sum('quantity');

        // Jika request via AJAX → kirim JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cartCount' => $cartCount,
            ]);
        }

        // Jika bukan AJAX → redirect
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }



    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);

        if ($request->action === 'increase') {
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();
        return redirect()->route('cart.index');
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function count()
    {
    $cart = Cart::where('session_id', session()->getId())->with('items')->first();
    $count = $cart ? $cart->items->sum('quantity') : 0;

    return response()->json(['count' => $count]);
    }

}
