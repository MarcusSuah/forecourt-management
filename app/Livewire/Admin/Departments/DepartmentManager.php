<?php

namespace App\Livewire\Admin\Departments;

use Livewire\Component;
use App\Models\Department;

class DepartmentManager extends Component
{
     public $departments, $name, $status = 'active', $departmentId;
    public $isOpen = false;
    public $isDeleteOpen = false;
    public $isShowOpen = false;
    public $selectedDepartment;

    public function render()
    {
         $this->departments = Department::latest()->get();
        return view('livewire.admin.departments.department-manager');
    }public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->isDeleteOpen = false;
        $this->isShowOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->status = 'active';
        $this->departmentId = null;
    }

    public function create()
    {
        $this->reset(['departmentId', 'name', 'status']);
        $this->status = 'active';
        $this->isOpen = true;
    }
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Department::updateOrCreate(['id' => $this->departmentId], [
            'name' => $this->name,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->departmentId ? 'Department updated successfully.' : 'Department created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->departmentId = $id;
        $this->name = $department->name;
        $this->status = $department->status;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->departmentId = $id;
        $this->isDeleteOpen = true;
    }

    public function delete()
    {
        Department::destroy($this->departmentId);
        $this->isDeleteOpen = false;
        session()->flash('message', 'Department deleted successfully.');
    }

    public function show($id)
    {
        $this->selectedDepartment = Department::findOrFail($id);
        $this->isShowOpen = true;
    }
}
