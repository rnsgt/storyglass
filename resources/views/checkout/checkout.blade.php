<!-- filepath: e:\laragon\www\storyglass\resources\views\checkout.blade.php -->
@extends('layouts.main')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #E6F2F2;">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="fw-bold mb-4 text-center">🧾 Checkout Pembelian</h3>

        @guest
            {{-- Jika belum login --}}
            <div class="alert alert-warning text-center rounded-4">
                <p class="mb-1">Kamu harus login sebelum melakukan checkout.</p>
                <a href="{{ route('login') }}" class="btn btn-success rounded-pill px-4 mt-2">Login Sekarang</a>
                <p class="mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            </div>
        @else
            {{-- Jika sudah login --}}
            
            {{-- Checkout Single Product (Beli Langsung) --}}
            @if($product ?? false)
                <div class="row g-4">
                    {{-- Produk --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3">🛍️ Produk yang Dibeli</h5>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('image/' . $product->gambar) }}" 
                                     alt="{{ $product->nama }}" 
                                     class="rounded-4 shadow-sm"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-semibold">{{ $product->nama }}</h6>
                                    <p class="text-muted small mb-2">{{ substr($product->deskripsi, 0, 50) }}...</p>
                                    <p class="text-success fw-semibold mb-0">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Pembelian --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3">📦 Data Pengiriman</h5>
                            <form action="{{ route('checkout.proses') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="type" value="single">
                                
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" id="nama" class="form-control rounded-4 @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama', Auth::user()->name) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control rounded-4 @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="tel" name="telepon" id="telepon" class="form-control rounded-4 @error('telepon') is-invalid @enderror" 
                                           placeholder="08xxxxxxxxxx" required>
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pilih Alamat -->
                                <div class="mb-3">
                                    <label class="form-label">Alamat Pengiriman</label>
                                    @if(isset($addresses) && $addresses->count() > 0)
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="address_type" id="use_saved" value="saved" checked>
                                            <label class="form-check-label" for="use_saved">
                                                Gunakan Alamat Tersimpan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="address_type" id="use_new" value="new">
                                            <label class="form-check-label" for="use_new">
                                                Alamat Baru
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <select name="saved_address_id" id="saved_address" class="form-select rounded-4 mb-2 @error('saved_address_id') is-invalid @enderror">
                                        <option value="">Pilih alamat...</option>
                                        @foreach($addresses as $addr)
                                        <option value="{{ $addr->id }}" {{ $addr->is_default ? 'selected' : '' }}>
                                            {{ $addr->label }} - {{ $addr->nama_penerima }} ({{ $addr->telepon }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('saved_address_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @else
                                    <input type="hidden" name="address_type" value="new">
                                    @endif
                                    
                                    <textarea name="alamat" id="alamat" rows="3" class="form-control rounded-4 @error('alamat') is-invalid @enderror" 
                                              placeholder="Jalan, No rumah, Kota, Provinsi, Kode Pos"></textarea>
                                    <small class="text-muted">Isi jika memilih "Alamat Baru" atau belum ada alamat tersimpan</small>
                                    @error('alamat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="payment_qris" value="qris" checked>
                                            <label class="btn btn-outline-primary w-100 rounded-4 py-3" for="payment_qris">
                                                <i class="bi bi-qr-code fs-4 d-block mb-2"></i>
                                                <strong>QRIS</strong>
                                                <small class="d-block text-muted">Scan & Bayar</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="payment_cod" value="cod">
                                            <label class="btn btn-outline-success w-100 rounded-4 py-3" for="payment_cod">
                                                <i class="bi bi-cash-coin fs-4 d-block mb-2"></i>
                                                <strong>COD</strong>
                                                <small class="d-block text-muted">Bayar di Tempat</small>
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah Pembelian</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control rounded-4 @error('jumlah') is-invalid @enderror" 
                                           min="1" value="1" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Summary Harga --}}
                                <div class="card bg-light border-0 rounded-4 p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Harga Satuan:</span>
                                        <span class="fw-semibold">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Jumlah:</span>
                                        <span class="fw-semibold" id="qty-display">1</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span class="fw-semibold text-success" id="subtotal-display">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total Pembayaran:</span>
                                        <span class="fw-bold text-danger fs-5" id="total-display">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">
                                    <i class="bi bi-check-circle"></i> Konfirmasi Pembelian
                                </button>
                                <a href="{{ route('produk.detail', $product->id) }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 mt-2">
                                    Batal
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

            {{-- Checkout dari Keranjang --}}
            @elseif(count($cart ?? []) > 0)
                <div class="row g-4">
                    {{-- Daftar Produk --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3">🛒 Produk di Keranjang ({{ count($cart) }} item)</h5>
                            
                            @forelse($cart as $key => $item)
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <img src="{{ asset('image/' . $item['image']) }}" 
                                             alt="{{ $item['nama'] }}" 
                                             class="rounded-3"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold mb-1">{{ $item['nama'] }}</h6>
                                            {{-- <p class="text-muted small mb-1">ID Produk: {{ $item['id'] }}</p> --}}
                                            <p class="text-muted small mb-0">
                                                Rp {{ number_format($item['harga'], 0, ',', '.') }} x {{ $item['jumlah'] }}
                                            </p>
                                            <p class="text-success fw-semibold mb-0">
                                                Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        @if(isset($item['jumlah']) && $item['jumlah'] > 0)
                                            <span class="badge bg-info rounded-pill py-2 px-3">{{ $item['jumlah'] }} pcs</span>
                                        @endif
                                        <div class="mt-2">
                                           <form action="{{ route('cart.remove', $item->id ?? $item['id']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dari keranjang?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-warning">
                                    Keranjang kosong
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Form & Summary --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                            <h5 class="fw-bold mb-3">📦 Data Pengiriman</h5>
                            <form action="{{ route('checkout.proses') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="cart">
                                
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" id="nama" class="form-control rounded-4 @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama', Auth::user()->name) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control rounded-4 @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="tel" name="telepon" id="telepon" class="form-control rounded-4 @error('telepon') is-invalid @enderror" 
                                           placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}" required>
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pilih Alamat -->
                                <div class="mb-3">
                                    <label class="form-label">Alamat Pengiriman</label>
                                    @if(isset($addresses) && $addresses->count() > 0)
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="address_type" id="use_saved_cart" value="saved" checked>
                                            <label class="form-check-label" for="use_saved_cart">
                                                Gunakan Alamat Tersimpan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="address_type" id="use_new_cart" value="new">
                                            <label class="form-check-label" for="use_new_cart">
                                                Alamat Baru
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <select name="saved_address_id" id="saved_address_cart" class="form-select rounded-4 mb-2 @error('saved_address_id') is-invalid @enderror">
                                        <option value="">Pilih alamat...</option>
                                        @foreach($addresses as $addr)
                                        <option value="{{ $addr->id }}" data-address="{{ $addr->full_address }}" {{ $addr->is_default ? 'selected' : '' }}>
                                            {{ $addr->label }} - {{ $addr->nama_penerima }} ({{ $addr->telepon }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('saved_address_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @else
                                    <input type="hidden" name="address_type" value="new">
                                    @endif
                                    
                                    <textarea name="alamat" id="alamat_cart" rows="3" class="form-control rounded-4 @error('alamat') is-invalid @enderror" 
                                              placeholder="Jalan, No rumah, Kota, Provinsi, Kode Pos">{{ old('alamat') }}</textarea>
                                    <small class="text-muted">Isi jika memilih "Alamat Baru" atau belum ada alamat tersimpan</small>
                                    @error('alamat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="payment_qris_cart" value="qris" checked>
                                            <label class="btn btn-outline-primary w-100 rounded-4 py-3" for="payment_qris_cart">
                                                <i class="bi bi-qr-code fs-4 d-block mb-2"></i>
                                                <strong>QRIS</strong>
                                                <small class="d-block text-muted">Scan & Bayar</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="payment_cod_cart" value="cod">
                                            <label class="btn btn-outline-success w-100 rounded-4 py-3" for="payment_cod_cart">
                                                <i class="bi bi-cash-coin fs-4 d-block mb-2"></i>
                                                <strong>COD</strong>
                                                <small class="d-block text-muted">Bayar di Tempat</small>
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Summary Harga --}}
                                <div class="card bg-light border-0 rounded-4 p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span>Jumlah Item:</span>
                                        <span>{{ count($cart) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span>Subtotal:</span>
                                        <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span>Ongkos Kirim:</span>
                                        <span class="text-success">Gratis</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total Pembayaran:</span>
                                        <span class="fw-bold text-danger fs-5">
                                            Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">
                                    <i class="bi bi-check-circle"></i> Bayar Sekarang
                                </button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 mt-2">
                                    Kembali ke Keranjang
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

            {{-- Keranjang Kosong & Tidak Ada Produk --}}
            @else
                <div class="alert alert-info text-center rounded-4 py-5">
                    <h5 class="mb-3">🛒 Keranjang Anda Kosong</h5>
                    <p class="mb-3">Tambahkan produk terlebih dahulu untuk melakukan checkout.</p>
                    <a href="{{ route('produk.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-shop"></i> Lihat Produk
                    </a>
                </div>
            @endif

        @endguest
    </div>
</div>

{{-- Script untuk update harga real-time (single product) --}}
<script>
    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        function updatePrice() {
            const hargaSatuan = {{ $product->harga ?? 0 }};
            const jumlah = parseInt(jumlahInput.value) || 1;
            const subtotal = hargaSatuan * jumlah;
            
            if (document.getElementById('qty-display')) {
                document.getElementById('qty-display').textContent = jumlah;
            }
            if (document.getElementById('subtotal-display')) {
                document.getElementById('subtotal-display').textContent = 
                    'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            }
            if (document.getElementById('total-display')) {
                document.getElementById('total-display').textContent = 
                    'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            }
        }
        
        jumlahInput.addEventListener('change', updatePrice);
        jumlahInput.addEventListener('keyup', updatePrice);
    }

    // Toggle alamat (single product)
    const useSaved = document.getElementById('use_saved');
    const useNew = document.getElementById('use_new');
    const savedAddress = document.getElementById('saved_address');
    const alamatInput = document.getElementById('alamat');
    
    if (useSaved && useNew && savedAddress && alamatInput) {
        function toggleAddress() {
            if (useSaved.checked) {
                savedAddress.style.display = 'block';
                savedAddress.required = true;
                alamatInput.style.display = 'none';
                alamatInput.required = false;
                alamatInput.value = '';
            } else {
                savedAddress.style.display = 'none';
                savedAddress.required = false;
                alamatInput.style.display = 'block';
                alamatInput.required = true;
            }
        }
        
        useSaved.addEventListener('change', toggleAddress);
        useNew.addEventListener('change', toggleAddress);
        toggleAddress();
    }

    // Toggle alamat (cart)
    const useSavedCart = document.getElementById('use_saved_cart');
    const useNewCart = document.getElementById('use_new_cart');
    const savedAddressCart = document.getElementById('saved_address_cart');
    const alamatCartInput = document.getElementById('alamat_cart');
    
    if (useSavedCart && useNewCart && savedAddressCart && alamatCartInput) {
        function toggleAddressCart() {
            if (useSavedCart.checked) {
                savedAddressCart.style.display = 'block';
                savedAddressCart.required = true;
                alamatCartInput.style.display = 'none';
                alamatCartInput.required = false;
                alamatCartInput.value = '';
            } else {
                savedAddressCart.style.display = 'none';
                savedAddressCart.required = false;
                alamatCartInput.style.display = 'block';
                alamatCartInput.required = true;
            }
        }
        
        useSavedCart.addEventListener('change', toggleAddressCart);
        useNewCart.addEventListener('change', toggleAddressCart);
        toggleAddressCart();
    }
</script>
@endsection