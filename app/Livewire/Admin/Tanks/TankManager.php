<?php

namespace App\Livewire\Admin\Tanks;

use App\Models\Product;
use App\Models\ServiceStation;
use App\Models\Tank;
use Livewire\Component;
use Livewire\WithPagination;

class TankManager extends Component
{
    use WithPagination;

    public $capacity;
    public $capacity_unit = 'liters';
    protected $paginationTheme = 'tailwind';
    public $products, $stations;
    public $tankId;
    public $station_id;
    public $product_id;
    public $nameInput;
    public $status = 'active';

    public $isOpen = false;
    public $isShowOpen = false;
    public $isDeleteOpen = false;

    public $selectedTank;
    public $generatedName;
    public $displayUnit = 'liters';

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id',
        'product_id' => 'required|exists:products,id',
        'nameInput' => 'required|numeric|min:1',
        'capacity' => 'required|numeric|min:0',
        'capacity_unit' => 'required|in:liters,gallons',
        'status' => 'required|in:active,inactive',
    ];

    public function mount()
    {
        $this->products = Product::all();
        $this->stations = ServiceStation::all();
    }

    public function render()
    {
        $tanks = Tank::with(['station', 'product'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.tanks.tank-manager', [
            'tanks' => $tanks,
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->isOpen = false;
        $this->isShowOpen = false;
        $this->isDeleteOpen = false;
    }

    public function resetForm()
    {
        $this->reset(['tankId', 'station_id', 'product_id', 'nameInput', 'status', 'generatedName', 'capacity', 'capacity_unit']);
        $this->status = 'active';
        $this->capacity_unit = 'liters';
    }

    public function updatedNameInput()
    {
        $this->generateTankName();
    }

    public function updatedProductId()
    {
        $this->generateTankName();
    }

    public function getCapacityInUsGallonsAttribute(): float
    {
        return $this->capacity / 3.78541;
    }

    public function getCapacityInImperialGallonsAttribute(): float
    {
        return $this->capacity / 4.54609;
    }

    public function generateTankName()
    {
        if ($this->product_id && is_numeric($this->nameInput)) {
            $product = Product::find($this->product_id);
            if ($product) {
                $prefix = strtoupper(substr($product->name, 0, 3));
                $this->generatedName = "{$prefix}-TANK-{$this->nameInput}";
            } else {
                $this->generatedName = null;
            }
        } else {
            $this->generatedName = null;
        }
    }

    public function store()
    {
        $this->validate();

        if ($this->tankId) {
            $convertedCapacity = $this->capacity_unit === 'gallons' ? $this->capacity * 3.78541 : $this->capacity;

            $tank = Tank::findOrFail($this->tankId);
            $tank->update([
                'station_id' => $this->station_id,
                'product_id' => $this->product_id,
                'status' => $this->status,
                'capacity' => $convertedCapacity,
            ]);

            session()->flash('message', 'Tank updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
            return;
        }

        // Validation for number of tanks to generate
        $this->validate([
            'nameInput' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->product_id);
        $prefix = strtoupper(substr($product->name, 0, 3));

        $existingTankCount = Tank::where('product_id', $this->product_id)->where('station_id', $this->station_id)->count();

        $startIndex = $existingTankCount + 1;
        $endIndex = $existingTankCount + intval($this->nameInput);

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $generatedName = "{$prefix}-TANK-{$i}";
            $convertedCapacity = $this->capacity_unit === 'gallons' ? $this->capacity * 3.78541 : $this->capacity;

            Tank::create([
                'station_id' => $this->station_id,
                'product_id' => $this->product_id,
                'name' => $generatedName,
                'capacity' => $convertedCapacity,
                'status' => $this->status,
            ]);
        }

        session()->flash('message', "{$this->nameInput} tanks created successfully.");
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tank = Tank::findOrFail($id);

        $this->tankId = $tank->id;
        $this->station_id = $tank->station_id;
        $this->product_id = $tank->product_id;
        $this->capacity = $tank->capacity;
        $this->status = $tank->status;

        if (preg_match('/\d+$/', $tank->name, $matches)) {
            $this->nameInput = $matches[0];
        }

        $this->generateTankName();
        $this->isOpen = true;
    }

    public function resetInputFields()
    {
        $this->tankId = null;
        $this->station_id = '';
        $this->product_id = '';
        $this->nameInput = '';
        $this->capacity = '';
        $this->capacity_unit = 'liters';
        $this->status = 'active';
    }

    public function show($id)
    {
        $this->selectedTank = Tank::with(['station', 'product'])->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->tankId = $id;
        $this->isDeleteOpen = true;
    }

    public function delete()
    {
        Tank::findOrFail($this->tankId)->delete();
        $this->isDeleteOpen = false;
        session()->flash('message', 'Tank deleted successfully.');
    }
}
