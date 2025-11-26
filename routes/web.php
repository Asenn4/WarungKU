<?php

use App\Livewire\Dashboard;
use App\Livewire\Products;
use App\Livewire\Cashier;
use App\Livewire\Transactions;
use Illuminate\Support\Facades\Route;

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