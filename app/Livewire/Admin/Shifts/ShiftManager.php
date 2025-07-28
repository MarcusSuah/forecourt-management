<?php

namespace App\Livewire\Admin\Shifts;

use Livewire\Component;
use App\Models\Shift;
use App\Models\ServiceStation;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ShiftManager extends Component
{
    use WithPagination;

    public $shiftId,
        $station_id,
        $name,
        $start_time,
        $end_time,
        $status = 'active';
    public $isOpen = false,
        $isShowModal = false,
        $isDeleteModal = false;
    public $selectedShift,
        $stations = [];
    public $isEditOpen = false;
    public $isDeleteOpen = false;
    public $isShowOpen = false;

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id',
        'name' => 'required|string|unique:shifts,name',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'status' => 'required|in:active,inactive',
    ];
    public function mount()
    {
        $this->stations = ServiceStation::all();
    }

    public function render()
    {
        return view('livewire.admin.shifts.shift-manager', [
            'shifts' => Shift::with('station')->latest()->paginate(10),
        ]);
    }
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetInputFields();
    }
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->isShowOpen = false;
        $this->isDeleteOpen = false;
        $this->resetInputFields();
    }

    public function resetForm()
    {
        $this->reset(['shiftId', 'station_id', 'name', 'start_time', 'end_time', 'status']);
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->shiftId = null;
        $this->station_id = '';
        $this->name = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->status = 'active';
        $this->selectedShift = null;
    }

    public function store()
    {
        $validated = $this->validate([
            'station_id' => 'required|exists:service_stations,id',
            'name' => 'required|string|unique:shifts,name,' . $this->shiftId,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);

        if ($this->shiftId) {
            Shift::find($this->shiftId)->update($validated);
        } else {
            Shift::create($validated);
        }
        session()->flash('message', 'Shift created successfully.');
        $this->closeModal();
        // $this->emit('refreshShiftList');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        $this->shiftId = $id;
        $this->station_id = $shift->station_id;
        $this->name = $shift->name;
        $this->start_time = \Carbon\Carbon::parse($shift->start_time)->format('H:i');
        $this->end_time = \Carbon\Carbon::parse($shift->end_time)->format('H:i');

        $this->status = $shift->status;

        session()->flash('message', 'Shift Updated successfully.');
        $this->isOpen = true;
    }

    public function show($id)
    {
        $this->selectedShift = Shift::with('station')->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function closeShowModal()
    {
        $this->isShowModal = false;
    }

    public function confirmDelete($id)
    {
        $this->shiftId = $id;
        $this->isDeleteOpen = true;
    }

    public function delete()
    {
        Shift::findOrFail($this->shiftId)->delete();
        $this->isDeleteModal = false;
        session()->flash('message', 'Shift deleted successfully.');
    }
}
