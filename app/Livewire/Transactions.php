<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Transactions extends Component
{
    use WithPagination;

    public $startDate = '';
    public $endDate = '';
    public $selectedTransaction = null;
    public $showDetail = false;

    public function mount()
    {
        $this->startDate = today()->format('Y-m-d');
        $this->endDate = today()->format('Y-m-d');
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function showTransactionDetail($id)
    {
        $this->selectedTransaction = Transaction::with('items.product')->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $query = Transaction::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.transactions', [
            'transactions' => $transactions,
        ])->layout('layouts.app');;
    }
}
