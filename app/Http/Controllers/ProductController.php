<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Tampilkan daftar produk (dengan search)
    public function index(Request $request)
    {
        $q = $request->query('search');
        if ($q) {
            $products = Product::where('nama', 'like', "%{$q}%")->get();
        } else {
            $products = Product::all();
        }

        return view('produk.index', compact('products'));
    }

    // Tampilkan detail produk
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('produk.detail', compact('product'));
    }
}
