<?php

use App\Livewire\Dashboard;
use App\Livewire\Products;
use App\Livewire\Cashier;
use App\Livewire\Transactions;
use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Models\Product;
use Milon\Barcode\DNS1D;

Route::get('/product/{sku}/barcode', function ($sku) {
    $product = Product::where('sku', $sku)->firstOrFail();
    // Menggunakan DNS1D untuk generate barcode
    $barcode = new DNS1D();
    // Mengatur format barcode ke EAN13 yang umum digunakan produk ritel
    // Pastikan SKU Anda adalah numerik dan valid untuk format ini
    return response($barcode->getBarcodePNG($product->sku, 'EAN13', 2, 60, [0, 0, 0], true))
        ->header('Content-Type', 'image/png');
})->name('product.barcode');


Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/products', Products::class)->name('products');
    Route::get('/cashier', Cashier::class)->name('cashier');
    Route::get('/transactions', Transactions::class)->name('transactions');

    Route::get('/api/search-product-by-sku/{sku}', [App\Http\Controllers\SyncController::class, 'searchProductBySKU'])->name('search.product.sku');

});

require __DIR__.'/auth.php';