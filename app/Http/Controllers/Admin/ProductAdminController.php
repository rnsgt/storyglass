<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Pastikan folder exist
            $destinationPath = public_path('image');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $image->move($destinationPath, $imageName);
            
            // Log untuk debugging
            Log::info('Gambar berhasil diupload: ' . $imageName);
        } else {
            Log::warning('Tidak ada file gambar yang diupload');
        }

        // Simpan ke Database
        $product = Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $imageName,
        ]);
        
        Log::info('Produk baru dibuat dengan ID: ' . $product->id . ', Gambar: ' . $imageName);

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
            if ($product->gambar && file_exists(public_path('image/' . $product->gambar))) {
                unlink(public_path('image/' . $product->gambar));
            }
            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image'), $imageName);
            $data['gambar'] = $imageName;
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
        if ($product->gambar && file_exists(public_path('image/' . $product->gambar))) {
            unlink(public_path('image/' . $product->gambar));
        }

        $product->delete();

        return redirect()->route('admin.products.list')->with('success', 'Produk berhasil dihapus!');
    }
}