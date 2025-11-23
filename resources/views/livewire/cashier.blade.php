<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Kasir</h2>
            <p class="mt-1 text-sm text-gray-600">Transaksi penjualan cepat</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Search & Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cari Produk</h3>
                    
                    <!-- Search Input -->
                    <input 
                        wire:model.live="search" 
                        type="text" 
                        placeholder="Ketik nama produk atau scan barcode..." 
                        class="w-full px-4 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        autofocus
                    >

                    <!-- Product Results -->
                    @if(strlen($search) >= 2 && $products->count() > 0)
                        <div class="mt-4 grid grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                            @foreach($products as $product)
                                <button 
                                    wire:click="addToCart({{ $product->id }})"
                                    class="text-left p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all"
                                >
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $product->sku }}</p>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="text-sm text-gray-500">Stok: {{ $product->stock }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @elseif(strlen($search) >= 2)
                        <div class="mt-4 text-center py-8 text-gray-500">
                            Produk tidak ditemukan
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shopping Cart -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Keranjang</h3>

                    @if(empty($cart))
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="mt-2">Keranjang kosong</p>
                        </div>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
                            @foreach($cart as $item)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-medium text-gray-900 text-sm">{{ $item['name'] }}</p>
                                        <button 
                                            wire:click="removeFromCart({{ $item['id'] }})"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <button 
                                                wire:click="updateQty({{ $item['id'] }}, {{ $item['qty'] - 1 }})"
                                                class="w-8 h-8 bg-gray-200 rounded-lg hover:bg-gray-300 font-bold"
                                            >-</button>
                                            <input 
                                                type="number" 
                                                wire:change="updateQty({{ $item['id'] }}, $event.target.value)"
                                                value="{{ $item['qty'] }}"
                                                class="w-16 text-center border border-gray-300 rounded px-2 py-1"
                                                min="1"
                                                max="{{ $item['stock'] }}"
                                            >
                                            <button 
                                                wire:click="updateQty({{ $item['id'] }}, {{ $item['qty'] + 1 }})"
                                                class="w-8 h-8 bg-gray-200 rounded-lg hover:bg-gray-300 font-bold"
                                            >+</button>
                                        </div>
                                        <p class="font-bold text-indigo-600">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t pt-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <button 
                            wire:click="processTransaction"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg transition-all text-lg"
                        >
                            Proses Transaksi
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        @if($showReceipt && $lastTransaction)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="text-center mb-4">
                                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-900 mt-2">Transaksi Berhasil!</h3>
                            </div>

                            <div id="receipt" class="bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
                                @include('components.receipt', ['transaction' => $lastTransaction])
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button 
                                onclick="window.print()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm"
                            >
                                🖨️ Print Struk
                            </button>
                            <button 
                                wire:click="closeReceipt"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- TETAPKAN <style> DI DALAM ROOT ELEMENT -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt, #receipt * {
                visibility: visible;
            }
            #receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
            }
        }
    </style>
</div>
