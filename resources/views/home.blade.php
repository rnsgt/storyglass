@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <section class="hero position-relative text-center text-white"
             style="background-image: url('{{ asset('image/banner.jpg') }}');
                    background-size: cover;
                    background-position: center;
                    height: 60vh;">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100"
             style="background-color: rgba(0,0,0,0.4);"></div>
        <div class="content position-relative d-flex flex-column justify-content-center align-items-center h-100">
            <h1 class="fw-bold display-5">Temukan Gayamu di StoryGlass </h1>
            <p class="lead mb-4">Koleksi kacamata stylish untuk semua momen</p>
            <a href="{{ route('produk.index') }}" class="btn btn-danger btn-lg rounded-pill shadow-sm px-4">
                Belanja Sekarang
            </a>
        </div>
    </section>

    <!-- Produk Unggulan -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Produk Unggulan</h2>
            <p class="text-muted">Pilihan terbaik dari koleksi kami</p>
        </div>

        <div class="row g-4">
            @foreach($produkUnggulan as $produk)
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <a href="{{ route('produk.detail', $produk->id) }}" class="text-decoration-none">
                            <img src="{{ $produk->gambar }}" class="card-img-top rounded-top-4"
                                 alt="{{ $produk->nama }}" style="height: 220px; object-fit: cover; cursor: pointer;">
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-semibold">{{ $produk->nama }}</h5>
                            <p class="text-danger fw-bold mb-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            <a href="{{ route('produk.detail', $produk->id) }}"
                               class="btn btn-outline-primary rounded-pill px-3">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
