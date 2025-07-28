<?php

namespace App\Livewire\Admin\Stations;

use App\Models\Dealer;
use App\Models\ServiceStation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class StationManager extends Component
{
    use WithFileUploads;

    public $stations;
    public $stationId;
    public $territory_manager;
    public $dealer_id;
    public $logo;
    public $name;
    public $email;
    public $phone;
    public $location;
    public $sap_number;
    public $opening_time;
    public $closing_time;
    public $is_active = true;
    public $currentLogo;
    public $logoPreview;

    public $isOpen = false;
    public $isDeleteOpen = false;
    public $isShowOpen = false;

    public $showStation;

    protected $rules = [
        'territory_manager' => 'nullable|string|max:255',
        'dealer_id' => 'required|exists:dealers,id',
        'name' => 'required|string|max:255|unique:service_stations,name',
        'email' => 'required|email|unique:service_stations,email',
        'phone' => 'required|string|unique:service_stations,phone',
        'location' => 'required|string|max:255',
        'sap_number' => 'required|string|unique:service_stations,sap_number',
        'opening_time' => 'required|date_format:H:i',
        'closing_time' => 'required|date_format:H:i',
        'is_active' => 'boolean',
        'logo' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->stations = ServiceStation::with('dealer')->latest()->get();
    }

    public function render()
    {
        return view('livewire.admin.stations.station-manager', [
            'dealers' => Dealer::all(),
        ]);
    }
    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->isOpen = false;
        $this->isDeleteOpen = false;
        $this->isShowOpen = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($id)
    {
        $station = ServiceStation::findOrFail($id);

        $this->stationId = $id;
        $this->territory_manager = $station->territory_manager;
        $this->dealer_id = $station->dealer_id;
        $this->logo = null; // reset image input
        $this->currentLogo = $station->logo;
        $this->name = $station->name;
        $this->email = $station->email;
        $this->phone = $station->phone;
        $this->location = $station->location;
        $this->sap_number = $station->sap_number;
        $this->opening_time = $station->opening_time ? date('H:i', strtotime($station->opening_time)) : null;
        $this->closing_time = $station->closing_time ? date('H:i', strtotime($station->closing_time)) : null;
        $this->is_active = $station->is_active;

        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|unique:service_stations,name,' . $this->stationId,
            'email' => 'required|email|unique:service_stations,email,' . $this->stationId,
            'phone' => 'required|string|unique:service_stations,phone,' . $this->stationId,
            'sap_number' => 'required|string|unique:service_stations,sap_number,' . $this->stationId,
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i|after:opening_time',
            'dealer_id' => 'required|exists:dealers,id',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $station = $this->stationId ? ServiceStation::findOrFail($this->stationId) : new ServiceStation();

        $station->territory_manager = $this->territory_manager;
        $station->dealer_id = $this->dealer_id;
        $station->name = $this->name;
        $station->email = $this->email;
        $station->phone = $this->phone;
        $station->location = $this->location;
        $station->sap_number = $this->sap_number;
        $station->is_active = $this->is_active;

        // Update times only if provided, else keep old value if editing
        if ($this->opening_time) {
            $station->opening_time = $this->opening_time;
        } elseif ($this->stationId && !$this->opening_time) {
            // keep old time (do nothing)
        }

        if ($this->closing_time) {
            $station->closing_time = $this->closing_time;
        } elseif ($this->stationId && !$this->closing_time) {
            // keep old time (do nothing)
        }

        // Handle logo upload if any
        if ($this->logo) {
            $station->logo = $this->logo->store('service-station-logos', 'public');
        }

        $station->save();

        session()->flash('message', $this->stationId ? 'Station updated successfully!' : 'Station created successfully!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function show($id)
    {
        $this->showStation = ServiceStation::with('dealer')->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function resetInputFields()
    {
        $this->stationId = null;
        $this->territory_manager = '';
        $this->dealer_id = null;
        $this->logo = null;
        $this->currentLogo = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->location = '';
        $this->sap_number = '';
        $this->opening_time = null;
        $this->closing_time = null;
        $this->is_active = true;
    }
    public function confirmDelete($id)
    {
        $this->stationId = $id;
        $this->isDeleteOpen = true;
    }

    public function delete()
    {
        $station = ServiceStation::findOrFail($this->stationId);

        if ($station->logo && Storage::exists('public/' . $station->logo)) {
            Storage::delete('public/' . $station->logo);
        }

        $station->delete();
        $this->closeModal();
        $this->stations = ServiceStation::with('dealer')->latest()->get();

        session()->flash('message', 'Station deleted successfully.');
    }

    private function resetForm()
    {
        $this->reset(['stationId', 'territory_manager', 'dealer_id', 'name', 'email', 'phone', 'location', 'sap_number', 'opening_time', 'closing_time', 'is_active', 'logo', 'currentLogo', 'logoPreview', 'showStation']);
        $this->is_active = true;
    }
}
