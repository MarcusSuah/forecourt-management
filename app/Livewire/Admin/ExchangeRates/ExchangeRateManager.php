<?php

namespace App\Livewire\Admin\ExchangeRates;

use Livewire\Component;
use App\Models\ExchangeRate;
use App\Models\Currency;

class ExchangeRateManager extends Component
{
    public $modalRate;
    public $rates = [];
    public $modalCurrencyId;
    public $exchangeRates;
    public $base_currency_id, $target_currency_id, $rate, $status = 'active';
    public $isOpen = false, $isEdit = false, $editId;
    public $currencies = [];
    public $modalCurrency = '';

    public function mount()
    {
        $this->currencies = Currency::where('status', 'active')->get();
    }

    public function render()
    {
        $this->exchangeRates = ExchangeRate::with('baseCurrency', 'targetCurrency')->latest()->get();
        return view('livewire.admin.exchange-rates.exchange-rate-manager');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
        $this->isEdit = false;
    }

    public function openEditModal($id)
    {
        $rate = ExchangeRate::findOrFail($id);
        $this->editId = $id;
        $this->base_currency_id = $rate->base_currency_id;
        $this->target_currency_id = $rate->target_currency_id;
        $this->rate = $rate->rate;
        $this->status = $rate->status;
        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'base_currency_id' => 'required|different:target_currency_id',
            'target_currency_id' => 'required',
            'rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        ExchangeRate::updateOrCreate(
            ['id' => $this->editId],
            [
                'base_currency_id' => $this->base_currency_id,
                'target_currency_id' => $this->target_currency_id,
                'rate' => $this->rate,
                'status' => $this->status,
            ]
        );

        session()->flash('message', $this->editId ? 'Exchange rate updated.' : 'Exchange rate created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    // public function updateRate()
    // {
    //     $this->validate([
    //         'modalRate' => 'required|numeric|min:0.0001',
    //     ]);

    //     if (!$this->modalCurrency) {
    //         return;
    //     }

    //     $baseCurrency = Currency::where('code', 'USD')->first();

    //     ExchangeRate::updateOrCreate(
    //         [
    //             'base_currency_id' => $baseCurrency->id,
    //             'target_currency_id' => $this->modalCurrency->id,
    //             'date' => now()->toDateString(),
    //         ],
    //         [
    //             'rate' => $this->modalRate,
    //             'status' => 'active',
    //         ]
    //     );

    //     $this->modalCurrency = '';
    //     $this->modalRate = '';
    //     $this->isOpen = false;

    //     session()->flash('message', 'Exchange rate updated successfully.');
    // }

    public function confirmDelete($id)
    {
        ExchangeRate::findOrFail($id)->delete();
        session()->flash('message', 'Exchange rate deleted.');
    }

    public function resetInput()
    {
        $this->editId = null;
        $this->base_currency_id = '';
        $this->target_currency_id = '';
        $this->rate = '';
        $this->status = 'active';
    }

    public function createExchangeRatesFromUSD()
    {
        $baseCurrency = Currency::where('code', 'USD')->firstOrFail();

        $currencies = Currency::where('code', '!=', 'USD')->where('status', 'active')->get();

        foreach ($currencies as $currency) {
            ExchangeRate::updateOrCreate(
                [
                    'base_currency_id' => $baseCurrency->id,
                    'target_currency_id' => $currency->id,
                    'date' => now()->toDateString(),
                ],
                [
                    'rate' => 0,
                    'status' => 'active',
                ]
            );
        }

        session()->flash('message', 'Exchange rates initialized for all non-USD currencies.');
    }

    public function openModal($currencyId)
    {
        $currency = Currency::findOrFail($currencyId);
        $this->modalCurrency = $currency;

        $baseCurrency = Currency::where('code', 'USD')->first();
        $existingRate = ExchangeRate::where('base_currency_id', $baseCurrency->id)
            ->where('target_currency_id', $currency->id)
            ->where('date', now()->toDateString())
            ->first();

        $this->modalRate = $existingRate?->rate ?? '';
        $this->isOpen = true;
        $this->isEdit = false;
    }

    public function saveRate($currencyId)
    {
        $this->validate([
            "rates.$currencyId" => 'required|numeric|min:0.0001',
        ]);

        $baseCurrency = Currency::where('code', 'USD')->first();

        ExchangeRate::updateOrCreate(
            [
                'base_currency_id' => $baseCurrency->id,
                'target_currency_id' => $currencyId,
                'date' => now()->toDateString(),
            ],
            [
                'rate' => $this->rates[$currencyId],
                'status' => 'active',
            ]
        );

        session()->flash('message', 'Exchange rate updated.');
    }
}
