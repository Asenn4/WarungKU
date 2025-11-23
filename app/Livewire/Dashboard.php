<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // Omzet hari ini
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total');

        // Grafik 7 hari terakhir
        $last7Days = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as revenue')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Format untuk Chart.js
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            
            $dayRevenue = $last7Days->firstWhere('date', $date);
            $chartData[] = $dayRevenue ? $dayRevenue->revenue : 0;
        }

        // Produk stok menipis
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'todayRevenue' => $todayRevenue,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
