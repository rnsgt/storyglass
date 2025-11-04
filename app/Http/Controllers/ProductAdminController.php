<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(15);
        return view('admin.produk.index', compact('products'));
    }

    public function create() {
        return view('admin.produk.create');
    }

    public function store(Request $r) {
        $data = $r->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048'
        ]);

        if ($r->hasFile('gambar')) {
            $path = $r->file('gambar')->store('products','public');
            $data['gambar'] = $path;
        }

        Product::create($data);
        return redirect()->route('admin.produk.index')->with('success','Produk dibuat');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $r, $id) {
        $product = Product::findOrFail($id);
        $data = $r->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048'
        ]);
        if ($r->hasFile('gambar')) {
            $path = $r->file('gambar')->store('products','public');
            $data['gambar'] = $path;
        }
        $product->update($data);
        return redirect()->route('admin.produk.index')->with('success','Produk diupdate');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.produk.index')->with('success','Produk dihapus');
    }
}
