<?php

namespace App\Livewire\Admin\UnitPrices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UnitPrice;
use App\Models\ServiceStation;
use App\Models\Product;
use Carbon\Carbon;

class PriceManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    // Modal states
    public $isOpen = false;
    public $showModal = false;
    public $deleteModal = false;

    // Form fields
    public $station_id;
    public $product_id;
    public $price;
    public $date;
    public $unitPriceId;

    // Show data
    public $showData = [];

    // Filters and search
    public $search = '';
    public $dateFilter = '';
    public $stationFilter = '';
    public $productFilter = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id',
        'product_id' => 'required|exists:products,id',
        'price' => 'required|numeric|min:0.0001',
        'date' => 'required|date',
    ];

    public function mount()
    {
        $this->date = now()->format('M d, Y') ;
    }

    public function render()
    {
        $query = UnitPrice::with(['station', 'product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('product', function ($productQuery) {
                        $productQuery->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('station', function ($stationQuery) {
                        $stationQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->dateFilter, function ($query) {
                $query->where('date', $this->dateFilter);
            })
            ->when($this->stationFilter, function ($query) {
                $query->where('station_id', $this->stationFilter);
            })
            ->when($this->productFilter, function ($query) {
                $query->where('product_id', $this->productFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $unitPrices = $query->paginate(10);
        $stations = ServiceStation::all();
        $products = Product::all();
        return view('livewire.admin.unit-prices.price-manager', [
            'unitPrices' => $unitPrices,
            'stations' => $stations,
            'products' => $products,
        ]);
    }
    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $unitPrice = UnitPrice::findOrFail($id);

        $this->unitPriceId = $id;
        $this->station_id = $unitPrice->station_id;
        $this->product_id = $unitPrice->product_id;
        $this->price = $unitPrice->price;
        $this->date = $unitPrice->date->format('M d, Y') ;

        $this->isOpen = true;
    }

    public function show($id)
    {
        $unitPrice = UnitPrice::with(['station', 'product'])->findOrFail($id);

        $this->showData = [
            'id' => $unitPrice->id,
            'station' => $unitPrice->station->name,
            'product' => $unitPrice->product->name,
            'price' => number_format($unitPrice->price, 2),
            'date' => $unitPrice->date,
            'created_at' => $unitPrice->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $unitPrice->updated_at->format('Y-m-d H:i:s'),
        ];

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'station_id' => $this->station_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'date' => $this->date,
        ];

        if ($this->unitPriceId) {
            UnitPrice::find($this->unitPriceId)->update($data);
            $message = 'Unit price updated successfully!';
        } else {
            UnitPrice::create($data);
            $message = 'Unit price created successfully!';
        }

        session()->flash('message', $message);
        $this->resetForm();
        $this->isOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->unitPriceId = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        UnitPrice::find($this->unitPriceId)->delete();
        session()->flash('message', 'Unit price deleted successfully!');
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
        $this->reset(['unitPriceId', 'station_id', 'product_id', 'price', 'date']);
        $this->date = now();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFilter = '';
        $this->stationFilter = '';
        $this->productFilter = '';
    }
}
