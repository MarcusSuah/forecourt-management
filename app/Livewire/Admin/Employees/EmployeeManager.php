<?php

namespace App\Livewire\Admin\Employees;

use App\Models\Employee;
use App\Models\ServiceStation;
use App\Models\Designation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EmployeeManager extends Component
{
    use WithFileUploads;

    public $employees, $stations, $designations;
    public $employeeId, $station_id, $designation_id, $fname, $lname, $email, $phone, $ssn;
    public $address, $dob, $gender, $emp_date, $image, $newImage;
    public $isOpen = false,
        $isShowOpen = false,
        $isDeleteOpen = false;
    public $selectedEmployee;
    public $employeeImage;

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id',
        'designation_id' => 'required|exists:designations,id',
        'fname' => 'nullable|string|max:255',
        'lname' => 'nullable|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'required|string|unique:employees,phone',
        'ssn' => 'required|string|unique:employees,ssn',
        'address' => 'nullable|string',
        'dob' => 'nullable|date',
        'gender' => 'required|in:male,female',
        'emp_date' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->stations = ServiceStation::all();
        $this->designations = Designation::all();
    }

    public function render()
    {
        $this->employees = Employee::with(['station', 'designation'])
            ->latest()
            ->get();
        return view('livewire.admin.employees.employee-manager');
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
        $this->selectedEmployee = null;
    }

    public function resetInputFields()
    {
        $this->employeeId = null;
        $this->station_id = '';
        $this->designation_id = '';
        $this->fname = '';
        $this->lname = '';
        $this->email = '';
        $this->phone = '';
        $this->ssn = '';
        $this->address = '';
        $this->dob = '';
        $this->gender = '';
        $this->emp_date = '';
        $this->image = null;
        $this->newImage = null;
    }

    public function store()
    {
        $rules = $this->rules;

        if ($this->employeeId) {
            $rules['email'] = 'required|email|unique:employees,email,' . $this->employeeId;
            $rules['phone'] = 'required|string|unique:employees,phone,' . $this->employeeId;
            $rules['ssn'] = 'required|string|unique:employees,ssn,' . $this->employeeId;
        }

        $this->validate($rules);

        $imagePath = $this->image ? $this->image->store('employees', 'public') : null;

        $data = [
            'station_id' => $this->station_id,
            'designation_id' => $this->designation_id,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'phone' => $this->phone,
            'ssn' => $this->ssn,
            'address' => $this->address,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'emp_date' => $this->emp_date,
        ];

        if ($this->image) {
            $data['image'] = $imagePath;
        }

        if ($this->employeeId) {
            $employee = Employee::findOrFail($this->employeeId);

            if ($this->image && $employee->image) {
                Storage::disk('public')->delete($employee->image);
            }

            $employee->update($data);
        } else {
            Employee::create($data);
        }
        session()->flash('message', 'Employee saved successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->station_id = $employee->station_id;
        $this->designation_id = $employee->designation_id;
        $this->fname = $employee->fname;
        $this->lname = $employee->lname;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->ssn = $employee->ssn;
        $this->address = $employee->address;
        $this->dob = $employee->dob;
        $this->gender = $employee->gender;
        $this->emp_date = $employee->emp_date;
        $this->image = $employee->image;

        $this->isOpen = true;
    }

    public function show($id)
    {
        $this->selectedEmployee = Employee::with(['station', 'designation'])->findOrFail($id);
        $this->isShowOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->employeeId = $id;
        $this->isDeleteOpen = true;

        session()->flash('message', 'Employee saved successfully.');
    }

    public function delete()
    {
        $employee = Employee::findOrFail($this->employeeId);

        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();
        $this->isDeleteOpen = false;
    }
}
