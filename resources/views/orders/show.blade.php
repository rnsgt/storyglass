@extends('layouts.main')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
            <h2 class="fw-bold"><i class="bi bi-receipt"></i> Detail Pesanan #{{ $order->id }}</h2>
        </div>

        <div class="col-lg-8">
            <!-- Daftar Produk -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4"><i class="bi bi-box-seam"></i> Produk Dibeli</h5>
                    <div class="list-group list-group-flush">
                        @foreach($order->items as $item)
                        <div class="list-group-item px-0 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}" 
                                         class="rounded" 
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         alt="{{ $item->product->nama ?? 'Produk' }}">
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold mb-1">{{ $item->product->nama ?? 'Produk Dihapus' }}</h6>
                                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </div>
                                <div class="col-auto">
                                    <h6 class="fw-bold mb-0" style="color: #558b8b;">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info Pengiriman -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h5>
                    <p class="mb-0" style="white-space: pre-line;">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <!-- Kolom Ringkasan -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-4"><i class="bi bi-info-circle"></i> Ringkasan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Status</span>
                        @php
                            $statusConfig = [
                                'pending' => ['badge' => 'bg-warning', 'text' => 'Menunggu Pembayaran'],
                                'processing' => ['badge' => 'bg-info', 'text' => 'Sedang Dikemas'],
                                'shipped' => ['badge' => 'bg-primary', 'text' => 'Dalam Perjalanan'],
                                'completed' => ['badge' => 'bg-success', 'text' => 'Selesai'],
                                'cancelled' => ['badge' => 'bg-danger', 'text' => 'Dibatalkan'],
                            ];
                            $status = $statusConfig[$order->status] ?? ['badge' => 'bg-secondary', 'text' => ucfirst($order->status)];
                        @endphp
                        <span class="badge {{ $status['badge'] }}">{{ $status['text'] }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tanggal Order</span>
                        <span class="fw-bold">{{ $order->created_at->format('d M Y') }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center py-3">
                        <h6 class="fw-bold mb-0">Total Bayar</h6>
                        <h4 class="fw-bold mb-0" style="color: #558b8b;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </h4>
                    </div>

                    @if($order->status == 'pending')
                    <div class="alert alert-warning mt-3 mb-0">
                        <small><i class="bi bi-exclamation-triangle"></i> Silakan selesaikan pembayaran agar pesanan segera diproses.</small>
                    </div>
                    @elseif($order->status == 'processing')
                    <div class="alert alert-info mt-3 mb-0">
                        <small><i class="bi bi-box-seam"></i> Pesanan sedang dikemas oleh penjual.</small>
                    </div>
                    @elseif($order->status == 'shipped')
                    <div class="alert alert-primary mt-3 mb-0">
                        <small><i class="bi bi-truck"></i> Pesanan sedang dalam perjalanan ke alamat Anda!</small>
                    </div>
                    @elseif($order->status == 'completed')
                    <div class="alert alert-success mt-3 mb-0">
                        <small><i class="bi bi-check-circle"></i> Pesanan telah selesai. Terima kasih sudah berbelanja!</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection