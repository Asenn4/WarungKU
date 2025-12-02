<!-- Receipt Modal -->
<div id="receipt-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4">
        
        <!-- Header -->
        <div class="bg-indigo-600 text-white p-4 rounded-t-lg print:hidden">
            <h3 class="text-xl font-bold text-center">Struk Pembayaran</h3>
        </div>

        <!-- Receipt Content - INI YANG AKAN DI-PRINT -->
        <div id="receipt-print-area" class="p-6">
            
            <!-- Store Info -->
            <div class="text-center mb-4 pb-3 border-b-2 border-dashed">
                <h2 class="text-xl font-bold uppercase">WARUNG SEMBAKO</h2>
                <p class="text-xs mt-1">Jl. Contoh No. 123, Kota Anda</p>
                <p class="text-xs">Telp: 0812-3456-7890</p>
            </div>

            <!-- Transaction Info -->
            <div class="text-xs mb-3 space-y-1">
                <div class="flex justify-between">
                    <span>No. Transaksi</span>
                    <span class="font-semibold">#{{ str_pad($lastTransaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span class="font-semibold">{{ $lastTransaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span class="font-semibold">{{ $lastTransaction->cashier }}</span>
                </div>
            </div>

            <!-- Items -->
            <div class="border-t-2 border-b-2 border-dashed py-3 mb-3">
                @foreach($lastTransaction->items as $item)
                <div class="mb-2">
                    <div class="flex justify-between text-sm font-semibold">
                        <span>{{ $item->product->name }}</span>
                        <span>{{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>{{ $item->qty }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mb-4">
                <div class="flex justify-between text-lg font-bold">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($lastTransaction->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-xs border-t-2 border-dashed pt-3">
                <p class="font-semibold">Terima kasih atas kunjungan Anda</p>
                <p class="mt-1">Barang yang sudah dibeli tidak dapat ditukar</p>
                <p class="mt-2">{{ now()->format('d/m/Y H:i:s') }}</p>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 p-4 bg-gray-50 rounded-b-lg print:hidden">
            <button 
                onclick="window.print()"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition"
            >
                🖨️ Print Struk
            </button>
            
            <button 
                wire:click="closeReceipt"
                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- Print JavaScript -->
<script>
function printReceipt() {
    window.print();
}
</script>

<!-- Enhanced Print Styles -->
<!-- Enhanced Print Styles -->
<style>
@media print {
    /* Reset semua */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* Hide everything first */
    body * {
        visibility: hidden;
    }
    
    /* Show only receipt */
    #receipt-print-area,
    #receipt-print-area * {
        visibility: visible;
    }
    
    /* CENTER & Position receipt */
    #receipt-print-area {
        position: absolute;
        left: 50% !important;
        top: 20mm !important;
        transform: translateX(-50%) !important;
        width: 80mm !important;
        max-width: 80mm !important;
        padding: 5mm !important;
        margin: 0 !important;
    }
    
    /* Force white background & black text */
    body,
    #receipt-modal,
    #receipt-print-area {
        background: white !important;
    }
    
    #receipt-print-area * {
        color: black !important;
        background: transparent !important;
    }
    
    /* Keep borders BLACK and VISIBLE */
    .border-dashed,
    .border-b-2,
    .border-t-2 {
        border-color: black !important;
        border-style: dashed !important;
    }
    
    /* Hide buttons completely */
    .print\:hidden {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Remove shadows & borders from modal */
    .shadow-2xl,
    .rounded-lg,
    .bg-white {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: none !important;
    }
    
    /* Font sizes untuk print */
    h2 {
        font-size: 16pt !important;
        line-height: 1.2 !important;
    }
    
    .text-lg {
        font-size: 12pt !important;
    }
    
    .text-sm {
        font-size: 10pt !important;
    }
    
    .text-xs {
        font-size: 8pt !important;
    }
    
    /* Spacing yang pas */
    .mb-2 {
        margin-bottom: 6px !important;
    }
    
    .mb-3 {
        margin-bottom: 8px !important;
    }
    
    .mb-4 {
        margin-bottom: 10px !important;
    }
    
    .pb-3 {
        padding-bottom: 8px !important;
    }
    
    .pt-3 {
        padding-top: 8px !important;
    }
    
    .py-3 {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }
    
    /* Page setup untuk thermal printer */
    @page {
        size: 80mm auto;
        margin: 0;
    }
}

/* Non-print styles (preview di modal) */
@media screen {
    #receipt-print-area {
        max-width: 80mm;
        margin: 0 auto;
    }
}
</style>