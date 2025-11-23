@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('profile.index') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Alamat</h2>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('profile.addresses.update', $address) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="label" class="form-label">Label Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('label') is-invalid @enderror" 
                                       id="label" name="label" value="{{ old('label', $address->label) }}" 
                                       placeholder="Contoh: Rumah, Kantor, Kos" required>
                                <div class="form-text">Beri nama untuk memudahkan identifikasi alamat</div>
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_penerima" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_penerima') is-invalid @enderror" 
                                       id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima', $address->nama_penerima) }}" required>
                                @error('nama_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                       id="telepon" name="telepon" value="{{ old('telepon', $address->telepon) }}" 
                                       placeholder="08xxxxxxxxxx" required>
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" required>{{ old('alamat', $address->alamat) }}</textarea>
                                <div class="form-text">Nama jalan, nomor rumah, RT/RW, dll.</div>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="kota" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                       id="kota" name="kota" value="{{ old('kota', $address->kota) }}" required>
                                @error('kota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('provinsi') is-invalid @enderror" 
                                       id="provinsi" name="provinsi" value="{{ old('provinsi', $address->provinsi) }}" required>
                                @error('provinsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                       id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $address->kode_pos) }}" 
                                       placeholder="12345" required>
                                @error('kode_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" 
                                           {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Jadikan sebagai alamat utama
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
