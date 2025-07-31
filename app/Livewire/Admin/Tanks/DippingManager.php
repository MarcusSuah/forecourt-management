<?php

namespace App\Livewire\Admin\Tanks;

use App\Models\TankDipping;
use App\Models\ServiceStation;
use App\Models\Shift;
use App\Models\Tank;
use App\Models\Product;
use App\Models\MeterCollection;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class DippingManager extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFilter, $dayFilter, $monthFilter, $yearFilter;
    public $stationFilter, $shiftFilter, $tankFilter;

    public $showModal = false;
    public $isEdit = false;
    public $form = [];

    protected $rules = [
        'form.date' => 'required|date',
        'form.station_id' => 'required|exists:service_stations,id',
        'form.shift_id' => 'required|exists:shifts,id',
        'form.product_id' => 'required|exists:products,id',
        'form.tank_id' => 'required|exists:tanks,id',
        'form.opening_dips' => 'required|integer',
        'form.qty_rec' => 'required|integer',
        'form.rtt' => 'required|integer',
        'form.closing_dips' => 'required|integer',
        'form.pump_sales_id' => 'required|exists:meter_collections,id',
        'form.threshold' => 'required|numeric',
        'form.status' => 'required|in:active,inactive',
    ];

    public function render()
    {
        $query = TankDipping::with(['station', 'shift', 'tank', 'product', 'pumpSales']);

        if ($this->search) {
            $query->whereHas('tank', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
        }

        if ($this->dateFilter) $query->whereDate('date', $this->dateFilter);
        if ($this->dayFilter) $query->whereDay('date', $this->dayFilter);
        if ($this->monthFilter) $query->whereMonth('date', $this->monthFilter);
        if ($this->yearFilter) $query->whereYear('date', $this->yearFilter);
        if ($this->stationFilter) $query->where('station_id', $this->stationFilter);
        if ($this->shiftFilter) $query->where('shift_id', $this->shiftFilter);
        if ($this->tankFilter) $query->where('tank_id', $this->tankFilter);

        return view('livewire.admin.tanks.dipping-manager', [
            'dippings' => $query->latest()->paginate(10),
            'stations' => ServiceStation::all(),
            'shifts' => Shift::all(),
            'tanks' => Tank::all(),
        ]);
    }

    public function create()
    {
        $this->reset('form');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit(TankDipping $dipping)
    {
        $this->form = $dipping->toArray();
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Auto-calculate
        $this->form['tank_sales'] = $this->form['qty_rec'] + $this->form['rtt'] + $this->form['opening_dips'] - $this->form['closing_dips'];
        $pumpSales = MeterCollection::find($this->form['pump_sales_id'])->total_sales ?? 0;
        $this->form['variance'] = $this->form['tank_sales'] - $pumpSales;

        $capacity = Tank::find($this->form['tank_id'])->capacity ?? 0;
        $this->form['capacity'] = $capacity;
        $this->form['aval_ullage'] = $this->form['closing_dips'] - $capacity;
        $this->form['sales_percentage'] = $capacity > 0 ? ($this->form['aval_ullage'] / $capacity) * 100 : 0;

        TankDipping::updateOrCreate(['id' => $this->form['id'] ?? null], $this->form);

        $this->showModal = false;
    }

    public function delete(TankDipping $dipping)
    {
        $dipping->delete();
    }
}
