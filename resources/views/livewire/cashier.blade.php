<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Heading -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Kasir</h2>
            <p class="mt-1 text-sm text-gray-600">Transaksi penjualan cepat</p>
        </div>

        <!-- Toggle Barcode Scanner -->
        <div class="mb-4 flex gap-3">
            <button 
                type="button"
                onclick="toggleBarcodeScanner()"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all flex items-center gap-2"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span id="scanner-btn-text">📷 Scan Barcode</span>
            </button>
        </div>

        <!-- Barcode Scanner -->
        <div id="barcode-scanner-container" class="mb-6 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Scanner Barcode</h3>
                    <button 
                        type="button"
                        onclick="stopBarcodeScanner()"
                        class="text-red-600 hover:text-red-800 font-medium"
                    >
                        ✕ Tutup
                    </button>
                </div>

                <div id="barcode-reader" class="w-full"></div>

                <div class="mt-4 text-sm text-gray-600 text-center">
                    Arahkan kamera ke barcode produk
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Search Produk -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cari Produk</h3>

                    <input 
                        wire:model.live="search" 
                        type="text" 
                        placeholder="Ketik nama produk atau scan barcode..." 
                        class="w-full px-4 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        autofocus
                    >

                    @if(strlen($search) >= 2 && $products->count() > 0)
                        <div class="mt-4 grid grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                            @foreach($products as $product)
                            <button 
                                wire:click="addToCart({{ $product->id }})"
                                class="text-left p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition"
                            >
                                <p class="font-semibold">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->sku }}</p>

                                <div class="mt-2 flex justify-between">
                                    <span class="text-lg font-bold text-indigo-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Stok: {{ $product->stock }}
                                    </span>
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

            <!-- Keranjang -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">

                    <h3 class="text-lg font-semibold mb-4">Keranjang</h3>

                    @if (empty($cart))
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="mt-2">Keranjang kosong</p>
                        </div>

                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
                            @foreach($cart as $item)
                            <div class="border p-3 rounded-lg">

                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-sm">{{ $item['name'] }}</p>

                                    <button 
                                        wire:click="removeFromCart({{ $item['id'] }})"
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>

                                <div class="flex justify-between items-center">

                                    <div class="flex items-center gap-2">
                                        <button 
                                            wire:click="updateQty({{ $item['id'] }}, {{ $item['qty'] - 1 }})"
                                            class="w-8 h-8 bg-gray-200 rounded-lg hover:bg-gray-300 font-bold"
                                        >-</button>

                                        <input 
                                            type="number"
                                            wire:change="updateQty({{ $item['id'] }}, $event.target.value)"
                                            value="{{ $item['qty'] }}"
                                            class="w-16 text-center border rounded px-2 py-1"
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
                            <div class="flex justify-between mb-2">
                                <span>Subtotal</span>
                                <span class="font-semibold">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-indigo-600">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button 
                            wire:click="processTransaction"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-lg shadow-lg text-lg"
                        >
                            Proses Transaksi
                        </button>

                    @endif

                </div>
            </div>
        </div>

        <!-- STRUK - MODAL -->
        @if($showReceipt && $lastTransaction)
            @include('components.receipt-modal')
        @endif

        <!-- PRINT STYLE -->
        <style>
            @media print {
                body * { visibility: hidden; }
                #receipt, #receipt * { visibility: visible; }
                #receipt {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 80mm;
                }
            }
        </style>

    </div>

    <!-- HTML5 QRCode -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
    let html5Qr;
    let scannerRunning = false;

    function toggleBarcodeScanner() {
        const container = document.getElementById("barcode-scanner-container");
        const text = document.getElementById("scanner-btn-text");

        if (!scannerRunning) {
            startBarcodeScanner();
            container.classList.remove("hidden");
            text.textContent = "⏹ Stop Scan";
        } else {
            stopBarcodeScanner();
            container.classList.add("hidden");
            text.textContent = "📷 Scan Barcode";
        }
    }

    function startBarcodeScanner() {
        if (scannerRunning) return;

        html5Qr = new Html5Qrcode("barcode-reader");

        html5Qr.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            (decodedText) => {
                beepSound();
                stopBarcodeScanner();
                scanBarcode(decodedText);
            }
        ).then(() => {
            scannerRunning = true;
        }).catch(console.error);
    }

    function stopBarcodeScanner() {
        if (!scannerRunning) return;

        html5Qr.stop().then(() => {
            html5Qr.clear();
            scannerRunning = false;
        });
    }

    function scanBarcode(rawSku) {

    let sku = rawSku.trim().replace(/\s+/g, "").replace(/\r?\n|\r/g, "");

    console.log("RAW:", rawSku);
    console.log("CLEAN:", sku);

    fetch(`/api/search-product-by-sku/${sku}`)
        .then(res => res.json())
        .then(json => {
            console.log("API RESPONSE:", json);

            if (json.success && json.data && json.data.id) {
                $wire.dispatch('addFromScan', { productId: json.data.id });
            } else {
                alert("Produk tidak ditemukan (SKU mismatch)!");
            }
        });
    }


    function beepSound() {
        new Audio("https://actions.google.com/sounds/v1/alarms/beep_short.ogg").play();
    }
    </script>


</div>
