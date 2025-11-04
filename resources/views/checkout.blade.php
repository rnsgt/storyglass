@extends('layouts.main')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #E6F2F2;">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="fw-bold mb-4 text-center">🧾 Checkout Pembelian</h3>

        @guest
            {{-- Jika belum login --}}
            <div class="alert alert-warning text-center rounded-4">
                <p class="mb-1">Kamu harus login sebelum melakukan checkout.</p>
                <a href="{{ route('login') }}" class="btn btn-success rounded-pill px-4 mt-2">Login Sekarang</a>
                <p class="mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            </div>
        @else
            {{-- Jika sudah login --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3">🛍️ Produk yang Dibeli</h5>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->nama }}" 
                                 class="rounded-4 shadow-sm"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <h6 class="fw-semibold">{{ $product->nama }}</h6>
                                <p class="text-success fw-semibold mb-0">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Pembelian --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3">📦 Data Pengiriman</h5>
                        <form action="{{ route('checkout.proses', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control rounded-4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control rounded-4" min="1" value="1" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 mt-2">
                                <i class="bi bi-check-circle"></i> Konfirmasi Pembelian
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endguest
    </div>
</div>
@endsection
