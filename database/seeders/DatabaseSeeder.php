<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@warung.com',
            'password' => Hash::make('password'),
        ]);

        // Seed products
        Product::create([
            'name' => 'Indomie Goreng', // Contoh barcode asli Indomie
            'sku' => '5285000390602',
            'price' => 3500,
            'stock' => 50,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Aqua 600ml', // Contoh barcode asli Aqua
            'sku' => '8886008101350',
            'price' => 4000,
            'stock' => 30,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Nescafe 230g',
            'sku' => '4600680009421',
            'price' => 10000,
            'stock' => 100,
            'low_stock_threshold' => 20,
        ]);

        Product::create([
            'name' => 'MSG jinomoto 100g',
            'sku' => '8880017530101',
            'price' => 5000,
            'stock' => 8,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Kopiko',
            'sku' => '8850580200398',
            'price' => 500,
            'stock' => 1000,
            'low_stock_threshold' => 50,
        ]);
    }
}