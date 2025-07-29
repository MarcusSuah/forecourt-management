<?php

namespace App\Livewire\Admin\Currencies;

use Livewire\Component;
use App\Models\Currency;

class CurrencyManager extends Component
{
    public $currencies;

    public $name,
        $code,
        $symbol,
        $status = 'active',
        $currencyId;
    public $isOpen = false,
        $isEdit = false;
    public $showModal = false;
    public $showData = [];

    public function render()
    {
        $this->currencies = Currency::latest()->get();
        return view('livewire.admin.currencies.currency-manager');
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->isOpen = true;
    }

    public function openEditModal($id)
    {
        $currency = Currency::findOrFail($id);
        $this->currencyId = $currency->id;
        $this->name = $currency->name;
        $this->code = $currency->code;
        $this->symbol = $currency->symbol;
        $this->status = $currency->status;
        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function openShowModal($id)
{
    $currency = Currency::findOrFail($id);
    $this->showData = [
        'id' => $currency->id,
        'name' => $currency->name,
        'code' => $currency->code,
        'symbol' => $currency->symbol,
        'status' => ucfirst($currency->status),
        'created_at' => $currency->created_at->toFormattedDateString(),
        'updated_at' => $currency->updated_at->toFormattedDateString(),
    ];
    $this->showModal = true;
}


    public function confirmDelete($id)
    {
        Currency::findOrFail($id)->delete();
        session()->flash('message', 'Currency deleted.');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:currencies,code,' . $this->currencyId,
            'symbol' => 'nullable|string|max:10',
        ]);

        Currency::updateOrCreate(
            ['id' => $this->currencyId],
            [
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'symbol' => $this->symbol,
                'status' => $this->status ?? 'active',
            ],
        );

        session()->flash('message', $this->isEdit ? 'Currency updated Successfully.' : 'Currency created Successfully.');
        $this->resetForm();
        $this->isOpen = false;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->code = '';
        $this->symbol = '';
        $this->status = 'active';
        $this->currencyId = null;
    }



}
