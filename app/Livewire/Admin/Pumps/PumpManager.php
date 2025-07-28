<?php

namespace App\Livewire\Admin\Pumps;

use App\Models\Pump;
use App\Models\Product;
use App\Models\ServiceStation;
use Livewire\Component;
use Livewire\WithPagination;

class PumpManager extends Component
{

    use WithPagination;


   protected $paginationTheme = 'tailwind';
    public $products, $stations;
    public $pumpId;
    public $station_id;
    public $product_id;
    public $nameInput;
    public $status = 'active';

    public $isOpen = false;
    public $isShowOpen = false;
    public $isDeleteOpen = false;

    public $selectedPump;
    public $generatedName;

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id',
        'product_id' => 'required|exists:products,id',
        'nameInput' => 'required|numeric|min:1',
        'status' => 'required|in:active,inactive',
    ];

    public function mount()
    {
        $this->products = Product::all();
        $this->stations = ServiceStation::all();
    }

public function render()
{
    $pumps = Pump::with(['station', 'product'])
        ->latest()
        ->paginate(10);

    return view('livewire.admin.pumps.pump-manager', [
        'pumps' => $pumps
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
        $this->reset(['pumpId', 'station_id', 'product_id', 'nameInput', 'status', 'generatedName']);
        $this->status = 'active';
    }

    public function updatedNameInput()
    {
        $this->generatePumpName();
    }

    public function updatedProductId()
    {
        $this->generatePumpName();
    }

    public function generatePumpName()
    {
        if ($this->product_id && is_numeric($this->nameInput)) {
            $product = Product::find($this->product_id);
            if ($product) {
                $prefix = strtoupper(substr($product->name, 0, 3));
                $this->generatedName = "{$prefix}-PUMP-{$this->nameInput}";
            } else {
                $this->generatedName = null;
            }
        } else {
            $this->generatedName = null;
        }
    }

    public function store()
    {
        $this->validate([
            'station_id' => 'required|exists:service_stations,id',
            'product_id' => 'required|exists:products,id',
            'status' => 'required|in:active,inactive',
        ]);

        // ✅ If editing, just update and exit early
        if ($this->pumpId) {
            $pump = Pump::findOrFail($this->pumpId);

            $pump->update([
                'station_id' => $this->station_id,
                'product_id' => $this->product_id,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Pump updated successfully.');

            $this->closeModal();
            $this->resetInputFields();
            return;
        }

        // If creating, require number input
        $this->validate([
            'nameInput' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->product_id);
        $prefix = strtoupper(substr($product->name, 0, 3));

        $existingPumpCount = Pump::where('product_id', $this->product_id)->where('station_id', $this->station_id)->count();

        $startIndex = $existingPumpCount + 1;
        $endIndex = $existingPumpCount + intval($this->nameInput);

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $generatedName = "{$prefix}-PUMP-{$i}";

            Pump::create([
                'station_id' => $this->station_id,
                'product_id' => $this->product_id,
                'name' => $generatedName,
                'status' => $this->status,
            ]);
        }

        session()->flash('message', "{$this->nameInput} pumps created successfully.");

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $pump = Pump::findOrFail($id);

        $this->pumpId = $pump->id;
        $this->station_id = $pump->station_id;
        $this->product_id = $pump->product_id;
        $this->status = $pump->status;

        // Extract numeric part for input field
        if (preg_match('/\d+$/', $pump->name, $matches)) {
            $this->nameInput = $matches[0];
        }

        $this->generatePumpName();
        $this->isOpen = true;
    }

    public function resetInputFields()
    {
        $this->pumpId = null;
        $this->station_id = '';
        $this->product_id = '';
        $this->nameInput = '';
        $this->status = 'active';
    }

    public function show($id)
    {
        $this->selectedPump = Pump::with(['station', 'product'])->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->pumpId = $id;
        $this->isDeleteOpen = true;
    }

    public function delete()
    {
        Pump::findOrFail($this->pumpId)->delete();
        $this->isDeleteOpen = false;
        session()->flash('message', 'Pump deleted successfully.');
    }
}
