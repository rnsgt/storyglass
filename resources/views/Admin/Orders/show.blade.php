<x-admin-layout title="Detail Pesanan" page-title="Detail Pesanan">
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan #{{ $order->id }}? Data tidak dapat dikembalikan!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus Pesanan
                    </button>
                </form>
            </div>
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
                                        <img src="{{ $item->product && $item->product->gambar ? asset('image/'.$item->product->gambar) : 'https://via.placeholder.com/60' }}" 
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
                
                <div class="mb-3">
                    <small class="text-muted">Metode Pembayaran</small>
                    <p class="mb-0">
                        @if($order->payment_method === 'qris')
                            <span class="badge bg-primary"><i class="bi bi-qr-code"></i> QRIS</span>
                        @elseif($order->payment_method === 'cod')
                            <span class="badge bg-success"><i class="bi bi-cash-coin"></i> COD (Bayar di Tempat)</span>
                        @else
                            <span class="badge bg-secondary">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                        @endif
                    </p>
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
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm">
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

                    <!-- Form Pengiriman (Muncul saat pilih Shipped) -->
                    <div id="shippingFields" style="display: none;">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Lengkapi info pengiriman sebelum mengirim pesanan</strong>
                        </div>
                        
                        <div class="mb-3">
                            <label for="courier_name" class="form-label">
                                <i class="bi bi-truck"></i> Nama Kurir/Ekspedisi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="courier_name" 
                                   id="courier_name" 
                                   class="form-control @error('courier_name') is-invalid @enderror" 
                                   placeholder="Contoh: JNE, SiCepat, J&T"
                                   value="{{ old('courier_name', $order->courier_name) }}">
                            @error('courier_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">
                                <i class="bi bi-upc-scan"></i> Nomor Resi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="tracking_number" 
                                   id="tracking_number" 
                                   class="form-control @error('tracking_number') is-invalid @enderror" 
                                   placeholder="Contoh: JP1234567890"
                                   value="{{ old('tracking_number', $order->tracking_number) }}">
                            @error('tracking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Pengiriman (Jika sudah shipped) -->
                    @if($order->status == 'shipped' && $order->tracking_number)
                    <div class="alert alert-success mb-3">
                        <strong><i class="bi bi-check-circle"></i> Pesanan Sudah Dikirim</strong><br>
                        <small>Kurir: <strong>{{ $order->courier_name }}</strong></small><br>
                        <small>Resi: <strong>{{ $order->tracking_number }}</strong></small><br>
                        <small>Dikirim: <strong>{{ $order->shipped_at ? $order->shipped_at->format('d M Y H:i') : '-' }}</strong></small>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const statusSelect = document.getElementById('status');
                    const shippingFields = document.getElementById('shippingFields');
                    const courierInput = document.getElementById('courier_name');
                    const trackingInput = document.getElementById('tracking_number');

                    function toggleShippingFields() {
                        if (statusSelect.value === 'shipped') {
                            shippingFields.style.display = 'block';
                            courierInput.required = true;
                            trackingInput.required = true;
                        } else {
                            shippingFields.style.display = 'none';
                            courierInput.required = false;
                            trackingInput.required = false;
                        }
                    }

                    // Check on load
                    toggleShippingFields();

                    // Check on change
                    statusSelect.addEventListener('change', toggleShippingFields);
                });
            </script>
        </div>
    </div>
</div>
</x-admin-layout>