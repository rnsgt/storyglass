@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="alert alert-success">
        <h4>Pembayaran Berhasil</h4>
        <p>Terima kasih — pesanan Anda telah diterima. Cek email untuk konfirmasi.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</div>
@endsection