<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk, dengan fungsionalitas pencarian.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $query = Product::query();

        // Jika ada kata kunci pencarian, filter produk berdasarkan nama
        if ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        }

        $products = $query->get();

        return view('produk.index', [
            'products' => $products,
            'keyword' => $keyword
        ]);
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('produk.detail', ['product' => $product]);
    }

    /**
     * Fungsi cari yang mungkin tidak terpakai lagi, tapi kita biarkan saja.
     */
    public function cari(Request $request)
    {
        // Logika ini dipindahkan ke method index() untuk simplisitas
        return $this->index($request);
    }
}




    

