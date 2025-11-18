<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Halaman beranda (home).
     */
    public function index()
    {
        // ambil beberapa produk terbaru untuk ditampilkan di home
        $latest = Product::latest()->take(8)->get();
        return view('home', compact('latest'));
    }

    /**
     * Halaman daftar produk.
     */
    public function produk()
    {
        $products = Product::paginate(12);
        return view('produk.index', compact('products'));
    }

    /**
     * Halaman tentang (tentang perusahaan).
     */
    public function tentang()
    {
        return view('tentang');
    }

    /**
     * Halaman kontak (opsional).
     */
    public function kontak()
    {
        return view('kontak');
    }
}