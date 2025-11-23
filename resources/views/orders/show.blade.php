@extends('layouts.main')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 px-4 sm:px-0">
            <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mb-2 block">&larr; Kembali ke Riwayat</a>
            <h2 class="font-bold text-2xl text-gray-800">Detail Pesanan #{{ $order->id }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mx-4 sm:mx-0">
            <!-- Kolom Utama -->
            <div class="md:col-span-2 space-y-6">
                <!-- Daftar Produk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Produk Dibeli</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <li class="py-4 flex gap-4">
                            <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/100' }}" 
                                 class="w-20 h-20 object-cover rounded bg-gray-100 flex-none">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->nama ?? 'Produk Dihapus' }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="font-bold text-gray-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Info Pengiriman -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Alamat Pengiriman</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Kolom Ringkasan -->
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Ringkasan</h3>
                    
                    <div class="flex justify-between mb-2 text-gray-600">
                        <span>Status</span>
                        <span class="font-bold uppercase text-indigo-600">{{ $order->status }}</span>
                    </div>
                    
                    <div class="flex justify-between mb-2 text-gray-600">
                        <span>Metode Bayar</span>
                        <span class="font-bold">{{ strtoupper($order->payment_method) }}</span>
                    </div>

                    <div class="border-t my-4 pt-4 flex justify-between items-center">
                        <span class="font-bold text-gray-800">Total Bayar</span>
                        <span class="font-bold text-xl text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($order->status == 'pending')
                    <div class="mt-4 p-3 bg-yellow-50 text-yellow-800 text-sm rounded border border-yellow-200">
                        Silakan selesaikan pembayaran agar pesanan segera diproses.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection