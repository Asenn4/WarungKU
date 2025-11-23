<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SyncController;

Route::prefix('v1')->group(function () {
    Route::get('/products', [SyncController::class, 'getProducts']);
    Route::post('/sync-transaction', [SyncController::class, 'syncTransaction']);
});
