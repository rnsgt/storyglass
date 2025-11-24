<x-admin-layout title="Detail Pelanggan" page-title="Detail Pelanggan: {{ $customer->name }}">
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Info User -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <span class="text-primary fw-bold" style="font-size: 2.5rem;">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="bi bi-envelope me-1"></i>{{ $customer->email }}
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-calendar-check me-1"></i>
                            Member sejak {{ $customer->created_at->format('M Y') }}
                        </span>
                    </div>
                    
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Total Pesanan</span>
                            <span class="fw-bold">{{ $customer->orders->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Total Belanja</span>
                            <span class="fw-bold text-success">
                                Rp {{ number_format($customer->orders->sum('total_price'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Order -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Order</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td><small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small></td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['class' => 'bg-warning', 'text' => 'Menunggu Pembayaran'],
                                                    'processing' => ['class' => 'bg-info', 'text' => 'Sedang Dikemas'],
                                                    'shipped' => ['class' => 'bg-primary', 'text' => 'Sedang Dikirim'],
                                                    'completed' => ['class' => 'bg-success', 'text' => 'Selesai'],
                                                    'cancelled' => ['class' => 'bg-danger', 'text' => 'Dibatalkan']
                                                ];
                                                $status = $statusConfig[$order->status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($order->status)];
                                            @endphp
                                            <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                        </td>
                                        <td><strong class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                            <p class="text-muted mb-0">Pelanggan ini belum pernah melakukan pembelian</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>