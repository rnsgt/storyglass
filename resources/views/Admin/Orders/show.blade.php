<x-admin-layout title="Detail Pesanan" page-title="Detail Pesanan">
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Kolom Kiri: Detail Item -->
        <div class="col-lg-8">
            <div class="table-container mb-4">
                <h5 class="mb-4"><i class="bi bi-box-seam"></i> Item Produk</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product && $item->product->gambar ? asset('image/' . $item->product->gambar) : 'https://via.placeholder.com/60' }}" 
                                             alt="{{ $item->product->nama ?? 'Produk' }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" 
                                             class="me-3">
                                        <div>
                                            <strong>{{ $item->product->nama ?? 'Produk Dihapus' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td><span class="badge bg-secondary">{{ $item->quantity }}</span></td>
                                <td class="text-end"><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Bayar:</strong></td>
                                <td class="text-end">
                                    <h5 class="mb-0" style="color: #558b8b;">
                                        <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                    </h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h5>
                <p class="mb-0" style="white-space: pre-line;">{{ $order->shipping_address ?? 'Alamat tidak tersedia' }}</p>
            </div>
        </div>

        <!-- Kolom Kanan: Status & Info Customer -->
        <div class="col-lg-4">
            <div class="table-container mb-4">
                <h5 class="mb-4"><i class="bi bi-person-circle"></i> Informasi Customer</h5>
                <div class="mb-3">
                    <small class="text-muted">Nama</small>
                    <p class="mb-0"><strong>{{ $order->user->name ?? 'Guest' }}</strong></p>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Email</small>
                    <p class="mb-0">{{ $order->user->email ?? '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Tanggal Order</small>
                    <p class="mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <small class="text-muted">Status Saat Ini</small>
                    <p class="mb-0">
                        <span class="badge status-{{ $order->status }}">
                            @if($order->status == 'pending') Menunggu Pembayaran
                            @elseif($order->status == 'processing') Sedang Diproses
                            @elseif($order->status == 'completed') Selesai
                            @elseif($order->status == 'cancelled') Dibatalkan
                            @endif
                        </span>
                    </p>
                </div>
            </div>

            <!-- Form Ganti Status -->
            <div class="table-container" style="border-top: 4px solid #558b8b;">
                <h5 class="mb-4"><i class="bi bi-pencil-square"></i> Update Status</h5>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Sedang Dikemas)</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dalam Perjalanan)</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>