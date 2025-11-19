@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="container-fluid">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Daftar Produk</h5>
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
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->nama }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $product->nama }}</div>
                            <small class="text-muted">{{ Str::limit($product->deskripsi, 40) }}</small>
                        </td>
                        <td><strong>Rp {{ number_format($product->harga, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge {{ $product->stok > 10 ? 'bg-success' : ($product->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $product->stok > 0 ? $product->stok . ' unit' : 'Habis' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;" 
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
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
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-1">Belum ada produk</p>
                            <small class="text-muted">Klik tombol "Tambah Produk" untuk menambahkan produk baru</small>
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
@endsection