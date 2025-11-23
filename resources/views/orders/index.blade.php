@extends('layouts.main')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 px-4 sm:px-0">
            <h2 class="font-bold text-2xl text-gray-800">Riwayat Pesanan Saya</h2>
            <p class="text-gray-600">Pantau status belanjaan King di sini.</p>
        </div>

        <div class="space-y-6 mx-4 sm:mx-0">
            @forelse($orders as $order)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-4 border-b pb-4">
                            <div>
                                <p class="text-sm text-gray-500">No. Order</p>
                                <p class="font-bold text-gray-800">#{{ $order->id }}</p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-medium">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-sm text-gray-500">Total Belanja</p>
                                <p class="font-bold text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $class }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Preview Item (Ambil 1 item pertama sebagai preview) -->
                        <div class="flex items-center gap-4">
                            @php $firstItem = $order->items->first(); @endphp
                            @if($firstItem && $firstItem->product)
                                <img src="{{ asset('storage/' . $firstItem->product->image) }}" class="w-16 h-16 object-cover rounded bg-gray-100" alt="">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $firstItem->product->nama }}</h4>
                                    @if($order->items->count() > 1)
                                        <p class="text-sm text-gray-500">+ {{ $order->items->count() - 1 }} produk lainnya</p>
                                    @else
                                        <p class="text-sm text-gray-500">x{{ $firstItem->quantity }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-gray-500 italic">Produk telah dihapus</div>
                            @endif
                        </div>
                        
                        <div class="mt-4 text-right">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                Lihat Detail Pesanan &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-10 rounded-lg shadow text-center">
                    <img src="https://via.placeholder.com/150?text=Empty" alt="Kosong" class="mx-auto h-24 w-24 opacity-50 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">King belum pernah belanja nih. Yuk mulai belanja!</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection