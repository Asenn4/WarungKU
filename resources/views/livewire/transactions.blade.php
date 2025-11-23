<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h2>
        <p class="mt-1 text-sm text-gray-600">Daftar semua transaksi penjualan</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input 
                    wire:model.live="startDate" 
                    type="date" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input 
                    wire:model.live="endDate" 
                    type="date" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
            <div class="flex items-end">
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-2 w-full">
                    <p class="text-sm text-gray-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $transactions->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->cashier }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button 
                                wire:click="showTransactionDetail({{ $transaction->id }})"
                                class="text-indigo-600 hover:text-indigo-900"
                            >
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>

    <!-- Transaction Detail Modal -->
    @if($showDetail && $selectedTransaction)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi #{{ str_pad($selectedTransaction->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $selectedTransaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Kasir: <span class="font-medium text-gray-900">{{ $selectedTransaction->cashier }}</span></p>
                        </div>

                        <!-- Items -->
                        <div class="border rounded-lg overflow-hidden mb-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($selectedTransaction->items as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->product->name }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-900">{{ $item->qty }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($selectedTransaction->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="closeDetail"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>