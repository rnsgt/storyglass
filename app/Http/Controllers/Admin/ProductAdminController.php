<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

        // Upload Gambar ke Cloudinary jika ada
        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'storyglass/products',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ]);
                $imageUrl = $uploadedFile->getSecurePath();
                Log::info('Gambar berhasil diupload ke Cloudinary: ' . $imageUrl);
            } catch (\Exception $e) {
                Log::error('Cloudinary upload error: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Gagal upload gambar ke Cloudinary']);
            }
        } else {
            Log::warning('Tidak ada file gambar yang diupload');
        }

        // Simpan ke Database
        $product = Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $imageUrl,
        ]);
        
        Log::info('Produk baru dibuat dengan ID: ' . $product->id . ', Gambar URL: ' . $imageUrl);

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
            try {
                // Upload gambar baru ke Cloudinary
                $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'storyglass/products',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ]);
                $data['gambar'] = $uploadedFile->getSecurePath();
                
                Log::info('Gambar produk diupdate ke Cloudinary: ' . $data['gambar']);
                
                // Note: Hapus gambar lama dari Cloudinary bisa dilakukan manual via dashboard
                // atau implement dengan extract public_id dari URL lama dan panggil Cloudinary::destroy($publicId)
            } catch (\Exception $e) {
                Log::error('Cloudinary upload error: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Gagal upload gambar ke Cloudinary']);
            }
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

        // Note: Hapus gambar dari Cloudinary bisa dilakukan manual via dashboard
        // atau extract public_id dari URL dan panggil Cloudinary::destroy($publicId)
        // Untuk sekarang, gambar tetap ada di Cloudinary (tidak masalah karena storage besar)

        $product->delete();

        return redirect()->route('admin.products.list')->with('success', 'Produk berhasil dihapus!');
    }
}