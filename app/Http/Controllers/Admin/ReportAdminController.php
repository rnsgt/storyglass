<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportAdminController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: Bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Ringkasan Utama (Card Statistik)
        $totalRevenue = Order::where('status', 'completed')
                             ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                             ->sum('total');

        $totalOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                            ->count();

        $completedOrders = Order::where('status', 'completed')
                                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                                ->count();

        // 2. Grafik Pendapatan Harian (Untuk Chart)
        $revenueData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', 'completed')
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // 3. Produk Terlaris (Top 5)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                      ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->with('product') // Eager load product details
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue', 
            'totalOrders', 
            'completedOrders', 
            'revenueData', 
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
}