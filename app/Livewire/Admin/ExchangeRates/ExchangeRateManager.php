<?php

namespace App\Livewire\Admin\ExchangeRates;

use Livewire\Component;
use App\Models\ExchangeRate;
use App\Models\Currency;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ExchangeRateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal states
    public $isOpen = false;
    public $showModal = false;
    public $deleteModal = false;

    // Form fields
    public $base_currency_id;
    public $target_currency_id;
    public $rate;
    public $date;
    public $status = 'active';
    public $exchangeRateId;
    // Show data
    public $showData = [];

    // Filters and search
    public $search = '';
    public $dateFilter = '';
    public $statusFilter = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';

    protected $rules = [
        'base_currency_id' => 'required|exists:currencies,id',
        'target_currency_id' => 'required|exists:currencies,id|different:base_currency_id',
        'rate' => 'required|numeric|min:0',
        'date' => 'required|date',
        'status' => 'required|in:active,inactive',
    ];

    public function mount()
    {
        $this->currencies = Currency::where('status', 'active')->get();
        $this->date = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = ExchangeRate::with(['baseCurrency', 'targetCurrency'])
            ->when($this->search, function ($query) {
                $query->whereHas('targetCurrency', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFilter, function ($query) {
                $query->where('date', $this->dateFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $exchangeRates = $query->paginate(10);

        $currencies = Currency::where('code', '!=', 'USD')->get();

        return view('livewire.admin.exchange-rates.exchange-rate-manager', [
            'exchangeRates' => $exchangeRates,
            'currencies' => $currencies,
            'usdCurrency' => Currency::where('code', 'USD')->first(),
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);

        $this->exchangeRateId = $id;
        $this->base_currency_id = $exchangeRate->base_currency_id;
        $this->target_currency_id = $exchangeRate->target_currency_id;
        $this->rate = $exchangeRate->rate;
        $this->date = $exchangeRate->date->format('Y-m-d');
        $this->status = $exchangeRate->status;

        $this->isOpen = true;
    }

    public function show($id)
    {
        $exchangeRate = ExchangeRate::with(['baseCurrency', 'targetCurrency'])->findOrFail($id);

        $this->showData = [
            'id' => $exchangeRate->id,
            'base_currency' => $exchangeRate->baseCurrency->name . ' (' . $exchangeRate->baseCurrency->code . ')',
            'target_currency' => $exchangeRate->targetCurrency->name . ' (' . $exchangeRate->targetCurrency->code . ')',
            'rate' => $exchangeRate->rate,
            'date' => $exchangeRate->date->format('Y-m-d'),
            'status' => ucfirst($exchangeRate->status),
            'created_at' => $exchangeRate->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $exchangeRate->updated_at->format('Y-m-d H:i:s'),
        ];

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'base_currency_id' => $this->base_currency_id,
            'target_currency_id' => $this->target_currency_id,
            'rate' => $this->rate,
            'date' => $this->date,
            'status' => $this->status,
        ];

        if ($this->exchangeRateId) {
            ExchangeRate::find($this->exchangeRateId)->update($data);
            $message = 'Exchange rate updated successfully!';
        } else {
            ExchangeRate::create($data);
            $message = 'Exchange rate created successfully!';
        }

        session()->flash('message', $message);
        $this->resetForm();
        $this->isOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->exchangeRateId = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        ExchangeRate::find($this->exchangeRateId)->delete();
        session()->flash('message', 'Exchange rate deleted successfully!');
        $this->deleteModal = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function resetForm()
    {
        $this->exchangeRateId = null;
        $this->base_currency_id = null;
        $this->target_currency_id = null;
        $this->rate = null;
        $this->date = now()->format('Y-m-d');
        $this->status = 'active';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFilter = '';
        $this->statusFilter = '';
    }
}
