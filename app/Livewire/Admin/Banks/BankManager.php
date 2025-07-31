<?php

namespace App\Livewire\Admin\Banks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bank;
use App\Models\ServiceStation;

class BankManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal states
    public $isOpen = false;
    public $showModal = false;
    public $deleteModal = false;
    // Form fields
    public $bank_id;
    public $station_id;
    public $account_name;
    public $account_number_usd;
    public $account_number_local;
    public $bank_name;
    public $branch;
    public $status = 'active';
    public $bankRecordId;
    // Show data
    public $showData = [];

    // Filters and search
    public $search = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'station_id' => 'required|exists:service_stations,id|unique:banks,station_id',
        'account_name' => 'required|string|max:255|unique:banks,account_name',
        'account_number_usd' => 'required|string|max:255|unique:banks,account_number_usd',
        'account_number_local' => 'required|string|max:255|unique:banks,account_number_local',
        'bank_name' => 'required|string|max:255',
        'branch' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ];

    public function mount()
    {
        $this->bank_id = 'BNK-' . strtoupper(uniqid());
    }
    public function render()
    {
        $query = Bank::with(['station'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('account_name', 'like', '%' . $this->search . '%')
                        ->orWhere('account_number_usd', 'like', '%' . $this->search . '%')
                        ->orWhere('account_number_local', 'like', '%' . $this->search . '%')
                        ->orWhere('bank_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('station', function ($stationQuery) {
                            $stationQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $banks = $query->paginate(10);
        $stations = ServiceStation::all();

        return view('livewire.admin.banks.bank-manager', [
            'banks' => $banks,
            'stations' => $stations,
        ]);
    }
    public function create()
    {
        $this->resetForm();
        $this->bank_id = 'BNK-' . strtoupper(uniqid());
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);

        $this->bankRecordId = $id;
        $this->bank_id = $bank->bank_id;
        $this->station_id = $bank->station_id;
        $this->account_name = $bank->account_name;
        $this->account_number_usd = $bank->account_number_usd;
        $this->account_number_local = $bank->account_number_local;
        $this->bank_name = $bank->bank_name;
        $this->branch = $bank->branch;
        $this->status = $bank->status;

        $this->isOpen = true;
    }

    public function show($id)
    {
        $bank = Bank::with(['station'])->findOrFail($id);

        $this->showData = [
            'id' => $bank->id,
            'bank_id' => $bank->bank_id,
            'station' => $bank->station ? $bank->station->name : 'N/A',
            'account_name' => $bank->account_name,
            'account_number_usd' => $bank->account_number_usd,
            'account_number_local' => $bank->account_number_local,
            'bank_name' => $bank->bank_name,
            'branch' => $bank->branch,
            'status' => ucfirst($bank->status),
            'created_at' => $bank->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $bank->updated_at->format('Y-m-d H:i:s'),
        ];

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'bank_id' => $this->bank_id,
            'station_id' => $this->station_id,
            'account_name' => $this->account_name,
            'account_number_usd' => $this->account_number_usd,
            'account_number_local' => $this->account_number_local,
            'bank_name' => $this->bank_name,
            'branch' => $this->branch,
            'status' => $this->status,
        ];

        if ($this->bankRecordId) {
            // Update existing record (ignore unique rules for current record)
            Bank::find($this->bankRecordId)->update($data);
            $message = 'Bank account updated successfully!';
        } else {
            // Create new record
            Bank::create($data);
            $message = 'Bank account created successfully!';
        }

        session()->flash('message', $message);
        $this->resetForm();
        $this->isOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->bankRecordId = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        Bank::find($this->bankRecordId)->delete();
        session()->flash('message', 'Bank account deleted successfully!');
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
        $this->reset([
            'bankRecordId',
            'bank_id',
            'station_id',
            'account_name',
            'account_number_usd',
            'account_number_local',
            'bank_name',
            'branch',
            'status',
        ]);
        $this->bank_id = 'BNK-' . strtoupper(uniqid());
        $this->status = 'active';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
    }

}
