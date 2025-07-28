<?php

namespace App\Livewire\Admin\Designations;

use App\Models\Designation;
use App\Models\Department;
use Livewire\Component;

class DesignationManager extends Component
{
    public $designations, $departments;
    public $designationId,
        $department_id,
        $name,
        $status = 'active';
    public $isOpen = false,
        $isShowOpen = false,
        $isDeleteOpen = false;
    public $selectedDesignation;

    public function mount()
    {
        $this->departments = Department::all();
        $this->designations = Designation::with('department')->latest()->get();
    }

    public function render()
    {
        return view('livewire.admin.designations.designation-manager');
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->resetInputFields();
        $this->isOpen = false;
        $this->isShowOpen = false;
        $this->isDeleteOpen = false;
    }

    public function resetInputFields()
    {
        $this->designationId = null;
        $this->department_id = null;
        $this->name = '';
        $this->status = 'active';
    }

    public function store()
    {
        $this->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Designation::updateOrCreate(
            ['id' => $this->designationId],
            [
                'department_id' => $this->department_id,
                'name' => $this->name,
                'status' => $this->status,
            ],
        );

        session()->flash('message', $this->designationId ? 'Designation updated.' : 'Designation created.');

        $this->closeModal();
        $this->designations = Designation::with('department')->latest()->get();
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);

        $this->designationId = $designation->id;
        $this->department_id = $designation->department_id;
        $this->name = $designation->name;
        $this->status = $designation->status;

        $this->openModal();
    }

    public function show($id)
    {
        $this->selectedDesignation = Designation::with('department')->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->designationId = $id;
        $this->isDeleteOpen = true;
    }

    public function deleteDesignation()
    {
        Designation::findOrFail($this->designationId)->delete();
        $this->closeModal();
        $this->designations = Designation::with('department')->latest()->get();
        session()->flash('message', 'Designation deleted.');
    }
}
