<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $productId = null;
    public $name = '';
    public $sku = '';
    public $price = '';
    public $stock = '';
    public $low_stock_threshold = 5;
    public $showModal = false;

    protected function rules()
{
    return [
        'name' => 'required|min:3',
        'sku' => $this->productId
            ? 'nullable|unique:products,sku,' . $this->productId   // saat update
            : 'nullable|unique:products,sku',                      // saat create
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'low_stock_threshold' => 'required|integer|min:0',
    ];
}


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->sku = '';
        $this->price = '';
        $this->stock = '';
        $this->low_stock_threshold = 5;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            
            $product->update([
                'name' => $this->name,
                'sku' => $this->sku,
                'price' => $this->price,
                'stock' => $this->stock,
                'low_stock_threshold' => $this->low_stock_threshold,
            ]);
            session()->flash('message', 'Produk berhasil diupdate!');
        } else {
            Product::create([
                'name' => $this->name,
                'sku' => $this->sku,
                'price' => $this->price,
                'stock' => $this->stock,
                'low_stock_threshold' => $this->low_stock_threshold,
            ]);
            session()->flash('message', 'Produk berhasil ditambahkan!');
        }

        $this->closeModal();
        $this->dispatch('product-saved');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->low_stock_threshold = $product->low_stock_threshold;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Produk berhasil dihapus!');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.products', [
            'products' => $products,
        ])->layout('layouts.app');;
    }
}
