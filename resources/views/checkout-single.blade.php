@extends('layouts.app')

@section('content')
<h1>Checkout Produk</h1>

<div>
    <h3>{{ $product->nama }}</h3>
    <p>{{ $product->deskripsi }}</p>
    <p>Harga: Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

    <form action="{{ route('checkout.proses') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <button type="submit">Bayar Sekarang</button>
    </form>
</div>
@endsection
