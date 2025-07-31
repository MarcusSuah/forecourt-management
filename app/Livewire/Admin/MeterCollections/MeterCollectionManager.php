<?php

namespace App\Livewire\Admin\MeterCollections;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MeterCollection;
use App\Models\ServiceStation;
use App\Models\Product;
use App\Models\Pump;
use App\Models\UnitPrice;
use App\Models\Shift;

class MeterCollectionManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    // Filter properties
    public $dateFilter = '';
    public $dayFilter = '';
    public $monthFilter = '';
    public $yearFilter = '';
    public $stationFilter = '';

    // Sorting properties
    public $sortField = 'date';
    public $sortDirection = 'desc';

    // Totals
    public $totals = [];
    public $totalRecords = 0;

    public $search = '';

    public $isOpen = false;
    public $showModal = false;
    public $deleteModal = false;

    public $meterCollectionId;
    public $date;
    public $station_id;
    public $product_id;
    public $pump_id;
    public $opening_meter = '';
    public $closing_meter = '';
    public $rtt = '';
    public $unit_price_at_sale = 0;
    public $unit_price_id = null;
    public $volume = 0;
    public $sales_in_gallon = 0;
    public $sales_turnover = 0;

    public $stations = [];
    public $products = [];
    public $pumps = [];
    public $showData = [];
    public $shift_id;
    public $shifts = [];

    protected $rules = [
        'date' => 'required|date',
        'shift_id' => 'nullable|exists:shifts,id',
        'station_id' => 'required|exists:service_stations,id',
        'product_id' => 'required|exists:products,id',
        'unit_price_id' => 'nullable|exists:unit_prices,id',
        'pump_id' => 'nullable|exists:pumps,id',
        'opening_meter' => 'required|numeric|min:0',
        'closing_meter' => 'required|numeric|min:0',
        'rtt' => 'required|numeric|min:0',
        'unit_price_at_sale' => 'required|numeric|min:0',
        'volume' => 'required|numeric|min:0',
        'sales_in_gallon' => 'required|numeric|min:0',
        'sales_turnover' => 'required|numeric|min:0',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'updateFields' => 'updateFields',
    ];

    public function mount()
    {
        $this->loadDropdownData();
        $this->calculateTotals();
        $this->date = now()->format('Y-m-d');
    }
    public function updatedDateFilter()
    {
        $this->calculateTotals();
    }

    public function updatedDayFilter()
    {
        $this->calculateTotals();
    }

    public function updatedMonthFilter()
    {
        $this->calculateTotals();
    }

    public function updatedYearFilter()
    {
        $this->calculateTotals();
    }

    public function updatedStationFilter()
    {
        $this->calculateTotals();
    }

    public function clearFilters()
    {
        $this->dateFilter = '';
        $this->dayFilter = '';
        $this->monthFilter = '';
        $this->yearFilter = '';
        $this->stationFilter = '';
        $this->calculateTotals();
    }

    public function getFilteredQuery()
    {
        $query = MeterCollection::with(['station', 'product', 'pump']);

        // Apply date filter
        if ($this->dateFilter) {
            $query->whereDate('date', $this->dateFilter);
        }

        // Apply day filter
        if ($this->dayFilter) {
            $query->whereDay('date', $this->dayFilter);
        }

        // Apply month filter
        if ($this->monthFilter) {
            $query->whereMonth('date', $this->monthFilter);
        }

        // Apply year filter
        if ($this->yearFilter) {
            $query->whereYear('date', $this->yearFilter);
        }

        // Apply station filter
        if ($this->stationFilter) {
            $query->where('station_id', $this->stationFilter);
        }

        return $query;
    }

    public function calculateTotals()
    {
        $query = $this->getFilteredQuery();

        $this->totals = [
            'volume' => $query->sum('volume'),
            'rtt' => $query->sum('rtt'),
            'sales_in_gallon' => $query->sum('sales_in_gallon'),
            'sales_turnover' => $query->sum('sales_turnover'),
        ];

        $this->totalRecords = MeterCollection::count();
    }

    public function getMeterCollectionsProperty()
    {
        return $this->getFilteredQuery()->orderBy($this->sortField, $this->sortDirection)->get();
    }

    public function getStationsProperty()
    {
        return ServiceStation::orderBy('name')->get();
    }

    public function loadDropdownData()
    {
        $this->stations = ServiceStation::all();
        $this->products = Product::all();
        $this->pumps = Pump::all();
        $this->shifts = Shift::orderBy('start_time')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
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

    public function updatedStationId()
    {
        $this->fetchUnitPrice();
        $this->loadShifts();
        $this->reset(['shift_id', 'pump_id']);
    }

    public function updatedProductId()
    {
        $this->fetchUnitPrice();
    }
    public function updateFields($fields)
    {
        foreach ($fields as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }
    }
    private function fetchUnitPrice()
    {
        if ($this->station_id && $this->product_id) {
            $unitPrice = UnitPrice::where('station_id', $this->station_id)->where('product_id', $this->product_id)->latest('date')->first();

            if ($unitPrice) {
                $this->unit_price_at_sale = $unitPrice->price;
                $this->unit_price_id = $unitPrice->id;
            } else {
                $this->unit_price_at_sale = 0;
                $this->unit_price_id = null;
            }

            $this->dispatch('unitPriceUpdated', ['price' => $this->unit_price_at_sale]);
        }
    }
    public function loadShifts()
    {
        if ($this->station_id) {
            $this->shifts = Shift::where('station_id', $this->station_id)->where('status', 'active')->get();
        } else {
            $this->shifts = [];
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $collection = MeterCollection::findOrFail($id);

        $this->meterCollectionId = $collection->id;
        $this->date = $collection->date->format('Y-m-d');
        $this->station_id = $collection->station_id;
        $this->product_id = $collection->product_id;
        $this->pump_id = $collection->pump_id;
        $this->unit_price_id = $collection->unit_price_id;
        $this->opening_meter = $collection->opening_meter;
        $this->closing_meter = $collection->closing_meter;
        $this->rtt = $collection->rtt;
        $this->unit_price_at_sale = $collection->unit_price_at_sale;
        $this->volume = $collection->volume;
        $this->sales_in_gallon = $collection->sales_in_gallon;
        $this->sales_turnover = $collection->sales_turnover;
        $this->shift_id = $collection->shift_id;

        $this->isOpen = true;
        $this->loadShifts();

        $this->isOpen = true;

        // Dispatch event to ensure Alpine.js picks up the initial values
        $this->dispatch('edit-mode', [
            'opening_meter' => $this->opening_meter,
            'closing_meter' => $this->closing_meter,
            'rtt' => $this->rtt,
            'unit_price_at_sale' => $this->unit_price_at_sale,
            'shift_id' => $this->shift_id,
        ]);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'date' => $this->date,
            'station_id' => $this->station_id,
            'product_id' => $this->product_id,
            'pump_id' => $this->pump_id,
            'unit_price_id' => $this->unit_price_id,
            'opening_meter' => $this->opening_meter,
            'closing_meter' => $this->closing_meter,
            'rtt' => $this->rtt,
            'unit_price_at_sale' => $this->unit_price_at_sale,
            'volume' => $this->volume,
            'sales_in_gallon' => $this->sales_in_gallon,
            'sales_turnover' => $this->sales_turnover,
            'shift_id' => $this->shift_id,
        ];

        if ($this->meterCollectionId) {
            MeterCollection::find($this->meterCollectionId)->update($data);
            session()->flash('message', 'Meter Collection updated successfully.');
        } else {
            MeterCollection::create($data);
            session()->flash('message', 'Meter Collection created successfully.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function show($id)
    {
        $collection = MeterCollection::with(['station', 'product', 'pump', 'shift'])->findOrFail($id);

        $this->showData = [
            'Date' => $collection->date->format('Y-m-d'),
            'Shift' => $collection->shift ? $collection->shift->name . ' (' . $collection->shift->time_range . ')' : 'N/A',
            'Station' => $collection->station->name,
            'Product' => $collection->product->name,
            'Pump' => $collection->pump?->name ?? 'N/A',
            'Opening Meter' => number_format($collection->opening_meter, 0),
            'Closing Meter' => number_format($collection->closing_meter, 0),
            'RTT' => number_format($collection->rtt, 2),
            'Unit Price' => number_format($collection->unit_price_at_sale, 2),
            'Volume' => number_format($collection->volume, 2),
            'Sales (Gallon)' => number_format($collection->sales_in_gallon, 2),
            'Sales Turnover' => number_format($collection->sales_turnover, 2),
        ];

        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->meterCollectionId = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        if ($this->meterCollectionId) {
            MeterCollection::find($this->meterCollectionId)->delete();
            session()->flash('message', 'Meter Collection deleted successfully.');
        }

        $this->deleteModal = false;
        $this->meterCollectionId = null;
    }

    public function resetForm()
    {
        $this->meterCollectionId = null;
        $this->date = now()->format('Y-m-d');
        $this->station_id = '';
        $this->shift_id = '';
        $this->product_id = '';
        $this->pump_id = '';
        $this->unit_price_id = null;
        $this->opening_meter = '';
        $this->closing_meter = '';
        $this->rtt = '';
        $this->unit_price_at_sale = '';
        $this->volume = '';
        $this->sales_in_gallon = '';
        $this->sales_turnover = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $collections = MeterCollection::with(['station', 'product', 'pump', 'shift'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('station', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('product', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('pump', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('shift', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(3);

        return view('livewire.admin.meter-collections.meter-collection-manager', [
            'meterCollections' => $collections,
            'stations' => $this->stations,
            'products' => $this->products,
            'pumps' => $this->pumps,
            'shifts' => $this->shifts,
            'totals' => $this->totals,
            'totalRecords' => $this->totalRecords,
        ]);
    }
}
