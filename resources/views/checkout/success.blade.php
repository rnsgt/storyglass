@extends('layouts.main')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Pembayaran Berhasil!</h2>
                    <p class="text-muted mb-4">Terima kasih! Pesanan Anda telah diterima dan akan segera diproses.</p>
                    
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Anda akan menerima email konfirmasi dalam beberapa saat.
                    </div>

                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">
                            <i class="bi bi-clock-history"></i> Lihat Pesanan Saya
                        </a>
                        <a href="{{ route('produk.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-shop"></i> Lanjut Belanja
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house-door"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection