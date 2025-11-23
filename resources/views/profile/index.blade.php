@extends('layouts.main')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Saya</h2>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit Profil
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Profil -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                            @if($user->isAdmin())
                                <i class="bi bi-shield-check me-1"></i>Administrator
                            @else
                                <i class="bi bi-person-check me-1"></i>Member
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Alamat -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</h5>
                        <a href="{{ route('profile.addresses.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Alamat
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($addresses->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-3">Belum ada alamat tersimpan</p>
                            <a href="{{ route('profile.addresses.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Alamat Pertama
                            </a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($addresses as $address)
                                <div class="list-group-item p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <h6 class="mb-0">{{ $address->label }}</h6>
                                                @if($address->is_default)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Utama
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mb-1 fw-semibold">{{ $address->nama_penerima }}</p>
                                            <p class="mb-1 text-muted"><i class="bi bi-telephone me-2"></i>{{ $address->telepon }}</p>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-geo-alt me-2"></i>{{ $address->full_address }}
                                            </p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if(!$address->is_default)
                                                    <li>
                                                        <form action="{{ route('profile.addresses.setDefault', $address) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-check-circle me-2"></i>Jadikan Utama
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('profile.addresses.edit', $address) }}">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
