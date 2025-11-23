@extends('layouts.main')

@section('title', 'Dashboard User')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-person-circle fs-1 text-primary mb-3"></i>
                    <h3 class="mb-3">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">Anda berhasil login sebagai user.</p>
                    <div class="mt-4 d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('produk.index') }}" class="btn btn-primary">
                            <i class="bi bi-shop"></i> Lihat Produk
                        </a>
                        <a href="{{ route('profile.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-circle"></i> Profil Saya
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-cart3"></i> Keranjang Saya
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-clock-history"></i> Riwayat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
