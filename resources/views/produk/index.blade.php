@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #CBE5E5; min-height: 100vh;">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color: #1E3A3A;">Koleksi Kacamata StoryGlass</h2>
        @if(isset($keyword))
    <p>Hasil pencarian untuk: <strong>{{ $keyword }}</strong></p>
@endif
        <div class="row justify-content-center g-4">
            @foreach ($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100 text-center">
                        {{-- Gambar Produk --}}
                        <a href="{{ route('produk.detail', $product->id) }}" class="text-decoration-none">
                            <img src="{{ asset('image/' . $product->gambar) }}" 
                                 alt="{{ $product->nama }}" 
                                 class="card-img-top rounded-top-4" 
                                 style="height: 250px; object-fit: cover; cursor: pointer;">
                        </a>

                        {{-- Isi Card --}}
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $product->nama }}</h5>
                            <p class="text-muted mb-1">
                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                            </p>
                            <p class="mb-3">
                                <span class="badge {{ $product->stok > 10 ? 'bg-success' : ($product->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    <i class="bi bi-box-seam"></i> Stok: {{ $product->stok > 0 ? $product->stok : 'Habis' }}
                                </span>
                            </p>

                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Beli Sekarang --}}
                                <a href="{{ route('checkout.beli', $product->id) }}" 
                                   class="btn btn-success rounded-pill px-3">
                                    Beli Sekarang
                                </a>

                                {{-- Tombol Tambah Keranjang --}}
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <button type="submit" class="btn btn-warning text-white rounded-pill px-3">
                                        + Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
