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
                                    <img src="{{ $item->product && $item->product->gambar ? asset('image/' . $item->product->gambar) : 'https://via.placeholder.com/80' }}" 
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h5>
                    <p class="mb-0" style="white-space: pre-line;">{{ $order->shipping_address }}</p>
                </div>
            </div>

            @if($order->status == 'shipped' || $order->status == 'completed')
            <!-- Info Tracking Pengiriman -->
            <div class="card shadow-sm border-0" style="border-left: 4px solid #558b8b !important;">
                <div class="card-body">
                    <h5 class="fw-bold mb-4" style="color: #558b8b;">
                        <i class="bi bi-truck"></i> Informasi Pengiriman
                    </h5>
                    
                    @if($order->courier_name && $order->tracking_number)
                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <div class="p-3 rounded" style="background-color: #f0f8f8; border: 1px solid #c4e2e0;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-building text-muted me-2"></i>
                                    <small class="text-muted">Kurir Pengiriman</small>
                                </div>
                                <h6 class="fw-bold mb-0">{{ strtoupper($order->courier_name) }}</h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f0f8f8; border: 1px solid #c4e2e0;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-receipt text-muted me-2"></i>
                                    <small class="text-muted">Nomor Resi</small>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 font-monospace">{{ $order->tracking_number }}</h6>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyResi('{{ $order->tracking_number }}')">
                                        <i class="bi bi-clipboard"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->shipped_at)
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="bi bi-clock"></i> Dikirim pada: 
                            <strong>{{ $order->shipped_at->format('d M Y, H:i') }} WIB</strong>
                        </small>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-warning mb-0">
                        <small><i class="bi bi-exclamation-circle"></i> Informasi resi belum tersedia. Silakan tunggu admin memperbarui status pengiriman.</small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Metode Pembayaran</span>
                        @if($order->payment_method === 'qris')
                            <span class="badge bg-primary"><i class="bi bi-qr-code"></i> QRIS</span>
                        @elseif($order->payment_method === 'cod')
                            <span class="badge bg-success"><i class="bi bi-cash-coin"></i> COD</span>
                        @else
                            <span class="badge bg-secondary">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                        @endif
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

<script>
function copyResi(resi) {
    navigator.clipboard.writeText(resi).then(function() {
        // Berhasil menyalin
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Tersalin';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }, function(err) {
        alert('Gagal menyalin nomor resi');
    });
}
</script>
@endsection