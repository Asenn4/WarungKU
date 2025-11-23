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
            'name' => 'Indomie Goreng',
            'sku' => 'IDM-001',
            'price' => 3500,
            'stock' => 50,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Aqua 600ml',
            'sku' => 'AQU-001',
            'price' => 4000,
            'stock' => 30,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Kopi Kapal Api',
            'sku' => 'KKA-001',
            'price' => 2000,
            'stock' => 100,
            'low_stock_threshold' => 20,
        ]);

        Product::create([
            'name' => 'Teh Pucuk Harum',
            'sku' => 'TPH-001',
            'price' => 5000,
            'stock' => 8,
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Beng Beng',
            'sku' => 'BB-001',
            'price' => 2500,
            'stock' => 60,
            'low_stock_threshold' => 15,
        ]);
    }
}