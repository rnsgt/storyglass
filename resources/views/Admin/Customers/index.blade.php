<x-admin-layout title="Data Pelanggan" page-title="Data Pelanggan">
<div class="container-fluid">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Daftar Pelanggan</h5>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Bergabung</th>
                        <th class="text-center">Total Pesanan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-primary"></i>
                                </div>
                                <strong>{{ $customer->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td><small class="text-muted">{{ $customer->created_at->format('d M Y') }}</small></td>
                        <td class="text-center">
                            @if($customer->orders_count > 0)
                                <span class="badge bg-info">{{ $customer->orders_count }} Pesanan</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" 
                               class="btn btn-sm btn-info me-1" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" 
                                  style="display: inline-block;" 
                                  onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-1">Belum ada pelanggan yang mendaftar</p>
                            <small class="text-muted">Data pelanggan akan muncul setelah ada user yang mendaftar</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
</x-admin-layout>