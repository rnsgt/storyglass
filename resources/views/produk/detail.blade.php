{{-- @extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ $produk->nama }}</h1>
    <img src="{{ asset('image/' . $produk->gambar) }}" width="300" alt="{{ $produk->nama }}">
    <p class="mt-3">Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
    <p>{{ $produk->deskripsi }}</p>
</div>
@endsection


 --}}

@extends('layouts.main')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #E6F2F2;">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <div class="row align-items-center g-4">
            {{-- Gambar Produk --}}
            <div class="col-md-5 text-center">
                <img src="{{ asset('image/' . $product->gambar) }}" 
                     alt="{{ $product->nama }}" 
                     class="img-fluid rounded-4 shadow-sm" 
                     style="max-height: 350px; object-fit: cover;">
            </div>

              {{-- Detail Produk --}}
            <div class="col-md-7">
                <h2 class="fw-bold mb-3">{{ $product->nama }}</h2>
                <p class="text-success fs-5 fw-semibold mb-2">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </p>
                <p class="mb-3">
                    <span class="badge {{ $product->stok > 10 ? 'bg-success' : ($product->stok > 0 ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2">
                        <i class="bi bi-box-seam me-1"></i> 
                        Stok: {{ $product->stok > 0 ? $product->stok . ' unit tersedia' : 'Habis' }}
                    </span>
                </p>
                <p class="text-muted">{{ $product->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}</p>

                <div class="mt-4 d-flex gap-3">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-3">
                        + Keranjang
                    </button>
                </form>
                    <a href="{{ route('checkout.beli', $product->id) }}" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-bag-fill"></i> Beli Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
        </a>
    </div>
</div>
@endsection
