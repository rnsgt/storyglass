@extends('layouts.main')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h2 class="fw-bold mb-2"><i class="bi bi-clock-history"></i> Riwayat Pesanan Saya</h2>
                <p class="text-muted">Pantau status belanjaan kamu di sini.</p>
            </div>

            <div class="row g-4">
            @forelse($orders as $order)
                <div class="col-12">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-4">
                            <div class="row align-items-center border-bottom pb-3 mb-3">
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <small class="text-muted">No. Order</small>
                                    <h6 class="fw-bold mb-0">#{{ $order->id }}</h6>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <small class="text-muted">Tanggal</small>
                                    <p class="mb-0 fw-medium">{{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <small class="text-muted">Total Belanja</small>
                                    <h6 class="mb-0 fw-bold" style="color: #558b8b;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <small class="text-muted d-block mb-1">Status</small>
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
                                    <span class="badge {{ $status['badge'] }} px-3 py-2">{{ $status['text'] }}</span>
                                </div>
                            </div>

                            <!-- Preview Item -->
                            <div class="d-flex align-items-center gap-3">
                                @php $firstItem = $order->items->first(); @endphp
                                @if($firstItem && $firstItem->product)
                                    <img src="{{ asset('image/' . $firstItem->product->gambar) }}" 
                                         class="rounded" 
                                         style="width: 70px; height: 70px; object-fit: cover;"
                                         alt="{{ $firstItem->product->nama }}">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $firstItem->product->nama }}</h6>
                                        @if($order->items->count() > 1)
                                            <small class="text-muted">+ {{ $order->items->count() - 1 }} produk lainnya</small>
                                        @else
                                            <small class="text-muted">Jumlah: {{ $firstItem->quantity }}</small>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted fst-italic">
                                        <i class="bi bi-exclamation-circle"></i> Produk telah dihapus
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h5 class="fw-bold mb-2">Belum Ada Pesanan</h5>
                            <p class="text-muted mb-4">Kamu belum pernah belanja nih. Yuk mulai belanja sekarang!</p>
                            <a href="{{ route('produk.index') }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
            </div>

            @if($orders->hasPages())
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection