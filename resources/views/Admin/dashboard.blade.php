<x-admin-layout title="Dashboard Admin" page-title="Dashboard">
<div class="container-fluid">
    <div class="row g-4 mb-4">
        <!-- Stat Card 1 -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3 class="mb-1">{{ $totalProducts ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Produk</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="icon">
                    <i class="bi bi-bag-check"></i>
                </div>
                <h3 class="mb-1">{{ $todayOrders ?? 0 }}</h3>
                <p class="text-muted mb-0">Pesanan Hari Ini</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3 class="mb-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                <p class="text-muted mb-0">Total Pendapatan</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card info">
                <div class="icon">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="mb-1">{{ $totalCustomers ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Pelanggan</p>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Produk Terbaru</h5>
                    <a href="{{ route('admin.products.list') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-list-ul"></i> Semua Produk
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->gambar)
                                        <img src="{{ asset('image/'.$product->gambar) }}" alt="{{ $product->nama }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    @else
                                        <div style="width: 50px; height: 50px; background: #ddd; border-radius: 5px;"></div>
                                    @endif
                                </td>
                                <td>{{ $product->nama }}</td>
                                <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $product->stok > 10 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $product->stok }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada produk</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
