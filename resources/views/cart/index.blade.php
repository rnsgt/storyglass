@extends('layouts.main')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($items->isEmpty())
                <div class="text-center py-5">
                    <h5 class="mt-3 text-muted">Keranjang belanjamu masih kosong 🛒</h5>
                    {{-- Arahkan ke rute produk Anda --}}
                     <a href="{{ route('produk.index') }}" class="btn btn-warning mt-3 text-white">Belanja Sekarang</a>
                </div>
            @else
            
                @foreach ($items as $item)
                {{-- Pengecekan keamanan: pastikan product ada --}}
                @if($item->product)
                <div class="d-flex border-bottom py-3 align-items-center">
                    {{-- Checkbox (Bisa dihilangkan jika Checkout langsung semua) --}}
                    <input type="checkbox" class="form-check-input me-3 item-checkbox"
                      data-harga="{{ $item->product->harga * $item->quantity }}" checked>

                    {{-- Gambar Produk --}}
                    <img src="{{ asset('image/' . $item->product->gambar) }}" 
                        alt="{{ $item->product->nama }}" 
                        width="80" height="80" class="rounded border me-3 object-fit-cover">

                    {{-- Info Produk --}}
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">{{ $item->product->nama }}</h6>
                        <div class="text-muted small">Variasi: Standar</div>
                        {{-- Harga Satuan --}}
                        <div class="text-muted small mt-1">Harga Satuan: 
                            <span class="fw-bold text-danger">Rp{{ number_format($item->product->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Quantity Control --}}
                    <div class="d-flex align-items-center me-3">
                        {{-- Formulir untuk mengurangi kuantitas --}}
                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-0" style="width:30px;">-</button>
                        </form>
                        
                        <input type="text" value="{{ $item->quantity }}" class="form-control text-center mx-0 rounded-0" style="width:50px;" readonly>
                        
                        {{-- Formulir untuk menambah kuantitas --}}
                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="increase">
                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-0" style="width:30px;">+</button>
                        </form>
                    </div>

                    {{-- Total Harga per Item --}}
                    <div class="ms-3 text-end" style="width: 120px;">
                        <span class="text-danger fw-bold">
                            Rp{{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="ms-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
                @endif {{-- Endif untuk pengecekan produk --}}
                @endforeach

                {{-- --- START RINGKASAN & TOTAL --- --}}

                    {{-- Kolom Kanan: Total Pembayaran --}}
                    <div class="d-flex my-4 h-100 justify-content-center align-items-center">
                         {{--buat ini jadi tengah  --}}
                        <div class="card shadow-sm border-danger w-100 d-flex justify-content-center">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Ringkasan Belanja</h5>
                                <div class="d-flex justify-content-between my-2">
                                    <span id="total-barang-text">Total Harga ({!! count($items) !!} Barang)</span>
                                    <span id="subtotal-text">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <h5 class="mb-0">Total Pembayaran</h5>
                                    <h5 id="total-text" class="text-danger fw-bold mb-0">Rp{{ number_format($total, 0, ',', '.') }}</h5>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="btn btn-danger px-4 py-2 w-100 mt-3">
                                    Lanjut ke Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- --- END RINGKASAN & TOTAL --- --}}

            @endif

        </div>
    </div>
</div>
{{-- SCRIPT: Hitung Total Otomatis Saat Checkbox Diklik --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const totalText = document.getElementById('total-text');
    const subtotalText = document.getElementById('subtotal-text');
    const totalBarangText = document.getElementById('total-barang-text');

    const formatRupiah = (angka) => {
        return 'Rp' + angka.toLocaleString('id-ID');
    };

    const updateTotal = () => {
        let total = 0;
        let jumlahBarang = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseInt(checkbox.dataset.harga);
                jumlahBarang++;
            }
        });

        totalText.textContent = formatRupiah(total);
        subtotalText.textContent = formatRupiah(total);
        totalBarangText.textContent = `Total Harga (${jumlahBarang} Barang)`;
    };

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    updateTotal(); // Jalankan saat pertama kali
});
</script>

@endsection