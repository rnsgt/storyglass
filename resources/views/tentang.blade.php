@extends('layouts.main')

@section('content')
<style>
    .about-page {
        background: linear-gradient(135deg, white 0%, #558b8b 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }
    
    .about-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 3rem;
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .about-card h1 {
        color: #84a9ac;
        font-weight: 700;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .about-card h1::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }
    
    .about-card h5 {
        color: #558b8b;
        font-weight: 600;
        margin-top: 2rem;
    }
    
    .about-card ul li {
        padding: 0.5rem 0;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .about-card ul li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #84a9ac;
        font-weight: bold;
    }
    
    .contact-box {
        background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%);
        color: white;
        border: none !important;
        border-radius: 15px;
        padding: 2rem !important;
        margin-top: 2rem;
    }
    
    .contact-box h6 {
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .contact-box .btn {
        background: white;
        color: #84a9ac;
        border: none;
        font-weight: 600;
        transition: transform 0.3s ease;
    }
    
    .contact-box .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="about-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="about-card">
                    <h1 class="mb-3">Tentang StoryGlass</h1>
                    <p class="lead text-muted">StoryGlass hadir untuk menyediakan kacamata berkualitas, desain modern, dan pengalaman belanja yang mudah serta aman.</p>

                    <h5 class="mt-4">Misi kami</h5>
                    <p>Menyediakan produk berkualitas dengan harga terjangkau dan layanan pelanggan yang cepat serta transparan.</p>

                    <h5 class="mt-3">Apa yang membuat kami berbeda</h5>
                    <ul>
                        <li>Kualitas teruji — produk melalui pengecekan kualitas sebelum dikirim.</li>
                        <li>Pengiriman cepat & aman — packing rapi dan tracking pesanan.</li>
                        <li>Garansi retur 7 hari — jika barang tidak sesuai.</li>
                        <li>Layanan pelanggan responsif — chat/email support.</li>
                    </ul>

                    <h5 class="mt-3">Sejarah singkat</h5>
                    <p class="text-muted">Dibangun sejak 2020, StoryGlass mulai dari toko online kecil dan terus berkembang berkat dukungan pelanggan setia.</p>

                    <div class="contact-box border rounded p-3 mt-4">
                        <h6>Hubungi kami</h6>
                        <p class="mb-1">Email: support@storyglass.test</p>
                        <p>Telepon: +62 812-XXX-XXXX</p>
                        <a href="{{ route('produk.index') }}" class="btn btn-light btn-sm mt-2">Lihat Produk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection