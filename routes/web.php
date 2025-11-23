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
});

require __DIR__.'/auth.php';