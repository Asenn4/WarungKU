<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Cashier extends Component
{
    public $search = '';
    public $cart = [];
    public $total = 0;
    public $showReceipt = false;
    public $lastTransaction = null;

    protected $listeners = ['product-selected' => 'addToCart'];

    public function mount()
    {
        $this->cart = [];
        $this->calculateTotal();
    }

    public function scanBarcode()
    {
        $barcode = trim($this->search);
    
        if (empty($barcode)) {
            return;
        }

        $product = Product::where('sku', $barcode)->first();

        if ($product) {
            $this->addToCart($product->id); 
        
            $this->search = '';
            $this->dispatch('focusScanner');

        } else {
            session()->flash('error', "Barcode/SKU '$barcode' tidak ditemukan!");
            $this->search = '';
            $this->dispatch('focusScanner');
        }
    }
        public function processCameraScan($barcodeValue)
    {
        $this->search = trim($barcodeValue); 
        $this->scanBarcode(); 
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Produk tidak tersedia atau stok habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
            } else {
                session()->flash('error', 'Stok tidak cukup!');
                return;
            }
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'stock' => $product->stock,
            ];
        }

        $this->calculateTotal();
        $this->search = '';
    }

    public function updateQty($productId, $qty)
    {
        if ($qty <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        if (isset($this->cart[$productId])) {
            $product = Product::find($productId);
            
            if ($qty > $product->stock) {
                session()->flash('error', 'Stok tidak cukup!');
                $this->cart[$productId]['qty'] = $product->stock;
            } else {
                $this->cart[$productId]['qty'] = $qty;
            }
        }

        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['qty'];
        }
    }

    public function processTransaction()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang masih kosong!');
            return;
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'total' => $this->total,
                'cashier' => Auth::user()->name,
            ]);

            // Create transaction items and update stock
            foreach ($this->cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['price'],
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            // Load transaction with items for receipt
            $this->lastTransaction = $transaction->load('items.product');
            $this->showReceipt = true;

            // Clear cart
            $this->cart = [];
            $this->calculateTotal();

            session()->flash('message', 'Transaksi berhasil!');
            $this->dispatch('focusScanner');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function closeReceipt()
    {
        $this->showReceipt = false;
        $this->lastTransaction = null;
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) >= 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->where('stock', '>', 0)
                ->limit(10)
                ->get();
        }

        return view('livewire.cashier', [
            'products' => $products,
        ])->layout('layouts.app');;
    }
}