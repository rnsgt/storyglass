<x-admin-layout title="Laporan Penjualan" page-title="Laporan Penjualan">
<div class="container-fluid">
    <!-- Filter Tanggal -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i>Tanggal Mulai
                    </label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i>Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kartu Statistik -->
    <div class="row g-4 mb-4">
        <!-- Total Pendapatan -->
        <div class="col-md-4">
            <div class="stat-card success">
                <div class="icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Total Pendapatan</small>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <small class="text-success">
                        <i class="bi bi-check-circle"></i> Pesanan Selesai
                    </small>
                </div>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="col-md-4">
            <div class="stat-card info">
                <div class="icon">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Total Pesanan Masuk</small>
                    <h3 class="mb-0 fw-bold">{{ $totalOrders }}</h3>
                    <small class="text-info">
                        <i class="bi bi-inbox"></i> Semua Status
                    </small>
                </div>
            </div>
        </div>

        <!-- Pesanan Selesai -->
        <div class="col-md-4">
            <div class="stat-card primary">
                <div class="icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Pesanan Selesai</small>
                    <h3 class="mb-0 fw-bold">{{ $completedOrders }}</h3>
                    <small class="text-primary">
                        <i class="bi bi-graph-up"></i> 
                        {{ $totalOrders > 0 ? number_format(($completedOrders / $totalOrders) * 100, 1) : 0 }}% dari total
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Grafik Pendapatan -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>Grafik Pendapatan Harian
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Top 5 Produk Terlaris
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">Produk</th>
                                    <th class="text-end pe-3">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $index => $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="badge bg-primary me-2" style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="me-2">
                                                    @if($item->product && $item->product->gambar)
                                                        <img src="{{ asset('image/' . $item->product->gambar) }}" 
                                                             alt="{{ $item->product->nama }}" 
                                                             style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div style="width: 45px; height: 45px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->product->nama ?? 'Produk Dihapus' }}</div>
                                                    <small class="text-muted">
                                                        Rp {{ number_format($item->product->harga ?? 0, 0, ',', '.') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-box-seam me-1"></i>{{ $item->total_qty }} pcs
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada data penjualan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<x-slot name="scripts">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($revenueData);

        const labels = revenueData.map(data => {
            const date = new Date(data.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const totals = revenueData.map(data => data.total);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: totals,
                    borderColor: '#558b8b',
                    backgroundColor: 'rgba(85, 139, 139, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#558b8b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Poppins'
                            },
                            color: '#333',
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            family: 'Poppins'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Poppins'
                        },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { 
                                        style: 'currency', 
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Poppins'
                            },
                            color: '#666',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Poppins'
                            },
                            color: '#666'
                        }
                    }
                }
            }
        });
    </script>
</x-slot>
</x-admin-layout>