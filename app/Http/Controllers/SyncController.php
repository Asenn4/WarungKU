<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SyncController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function syncTransaction(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|array',
            'total' => 'required|numeric',
            'cashier' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'total' => $validated['total'],
                'cashier' => $validated['cashier'],
            ]);

            // Create transaction items and update stock
            foreach ($validated['cart'] as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['price'],
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchProductBySKU($sku)
    {
        try {
            // Search product by SKU
            $product = Product::where('sku', $sku)
                ->orWhere('sku', 'LIKE', '%' . $sku . '%')
                ->first();

            if ($product) {
                // Check if product has stock
                if ($product->stock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk ditemukan tapi stok habis',
                        'product' => null
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Produk ditemukan',
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => $product->price,
                        'stock' => $product->stock
                    ]
                ]);
            }

            // Product not found
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'product' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'product' => null
            ], 500);
        }
    }

}