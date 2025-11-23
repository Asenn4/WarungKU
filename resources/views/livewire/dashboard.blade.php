<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600">Overview penjualan dan stok produk</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Today Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500">Omzet Hari Ini</dt>
                    <dd class="text-2xl font-bold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</dd>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500">Stok Menipis</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $lowStockProducts->count() }} Produk</dd>
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow p-6">
            <div class="text-center">
                <h3 class="text-white font-semibold mb-3">Mulai Transaksi</h3>
                <a href="{{ route('cashier') }}" class="inline-block bg-white text-indigo-600 font-bold py-2 px-6 rounded-lg hover:bg-gray-100 transition">
                    Buka Kasir →
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Omzet 7 Hari Terakhir</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Stok Menipis</h3>
            @if($lowStockProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($lowStockProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">SKU: {{ $product->sku ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-red-600">{{ $product->stock }}</p>
                                <p class="text-xs text-gray-500">unit</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-gray-500">Semua produk stok aman</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: @json($chartData),
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>