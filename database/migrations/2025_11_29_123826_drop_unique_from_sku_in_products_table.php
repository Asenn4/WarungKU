<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus unique index pada kolom SKU
            $table->dropUnique('products_sku_unique');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan lagi index unique jika rollback
            $table->unique('sku', 'products_sku_unique');
        });
    }
};
