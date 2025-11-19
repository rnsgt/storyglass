<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductAdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan daftar produk.
     */
    public function index()
    {
        // Ambil data produk terbaru, 10 per halaman
        $products = Product::latest()->paginate(10);

        // Tampilkan ke view dashboard admin
        return view('admin.dashboard', compact('products'));
    }

    /**
     * Menampilkan halaman manajemen produk.
     */
    public function listProducts()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload Gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan ke Database
        Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.list')->with('success', 'Produk berhasil ditambahkan, King!');
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update produk yang ada.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data dasar
        $data = $request->only(['nama', 'deskripsi', 'harga', 'stok']);

        // Cek jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.list')->with('success', 'Produk berhasil diperbarui, King!');
    }

    /**
     * Hapus produk.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar dari storage biar hemat
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.list')->with('success', 'Produk berhasil dihapus!');
    }
}