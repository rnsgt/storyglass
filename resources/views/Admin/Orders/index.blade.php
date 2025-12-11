<x-admin-layout title="Manajemen Pesanan" page-title="Daftar Pesanan Masuk">
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Semua Pesanan</h5>
            <div>
                <span class="badge bg-secondary">Total: {{ $orders->total() }}</span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <i class="bi bi-person-circle"></i> 
                            {{ $order->user->name ?? 'Guest' }}
                        </td>
                        <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge status-{{ $order->status }}">
                                @if($order->status == 'pending') Menunggu Bayar
                                @elseif($order->status == 'processing') Sedang Dikemas
                                @elseif($order->status == 'shipped') Dalam Perjalanan
                                @elseif($order->status == 'completed') Selesai
                                @elseif($order->status == 'cancelled') Dibatalkan
                                @else {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <small>{{ $order->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesanan #{{ $order->id }}? Data tidak dapat dikembalikan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Pesanan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Belum ada pesanan masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
</x-admin-layout>