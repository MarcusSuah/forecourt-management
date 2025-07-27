<?php

namespace App\Livewire\Admin\Dealers;

use Livewire\Component;

use App\Models\Dealer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;
class DealerManager extends Component
{
    use WithPagination, WithFileUploads;

    public $dealerId;
    public $fname,
        $lname,
        $dob,
        $gender,
        $email,
        $phone,
        $address,
        $status = 'Pending',
        $image,
        $imagePreview,
        $currentImage;
    public $isOpen = false;
    public $confirmingDeletion = false;
    public $dealerToDelete;
    public $showModal = false;
    public $selectedDealer;
    public $search = '';
    protected $rules = [
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'dob' => 'required|date',
        'gender' => 'required|in:male,female',
        'email' => 'required|email|unique:dealers,email',
        'phone' => 'required|unique:dealers,phone',
        'address' => 'required|string',
        'status' => 'required|in:Pending,Active,Suspended,Terminated',
        'image' => 'nullable|image|max:2048',
    ];

    protected $paginationTheme = 'tailwind';
    public function render()
    {
        $dealers = Dealer::query()
            ->where('fname', 'like', "%{$this->search}%")
            ->orWhere('lname', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(5);

        return view('livewire.admin.dealers.dealer-manager', compact('dealers'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->showModal = false;
        $this->selectedDealer = null;
    }
    public function resetInputFields()
    {
        $this->dealerId = null;
        $this->fname = '';
        $this->lname = '';
        $this->dob = '';
        $this->gender = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->status = 'Pending';
        $this->image = null;
        $this->imagePreview = null;
        $this->currentImage = null;
    }

    public function resetForm()
    {
        $this->dealerId = null;
        $this->fname = '';
        $this->lname = '';
        $this->dob = '';
        $this->gender = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->status = 'Pending';
        $this->image = null;
        $this->currentImage = null;
        $this->imagePreview = null;
    }

    public function updatedImage()
    {
        $this->validateOnly('image');

        if ($this->image) {
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    public function store()
    {
        $this->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|unique:dealers,email,' . $this->dealerId,
            'phone' => 'required|string|unique:dealers,phone,' . $this->dealerId,
            'address' => 'required|string',
            'status' => 'required|in:Pending,Active,Suspended,Terminated',
            'image' => $this->dealerId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $imagePath = $this->currentImage;

        if ($this->image) {
            $imagePath = $this->image->store('dealers', 'public');
        }

        Dealer::updateOrCreate(
            ['id' => $this->dealerId],
            [
                'fname' => $this->fname,
                'lname' => $this->lname,
                'dob' => $this->dob,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'status' => $this->status,
                'image' => $imagePath,
            ],
        );

        session()->flash('message', $this->dealerId ? 'Dealer updated successfully.' : 'Dealer created successfully.');

        $this->resetForm();
        $this->closeModal();
    }

    public function edit($id)
    {
        $dealer = Dealer::findOrFail($id);

        $this->dealerId = $id;
        $this->fname = $dealer->fname;
        $this->lname = $dealer->lname;
        $this->dob = Carbon::parse($dealer->dob)->format('Y-m-d');
        $this->gender = $dealer->gender;
        $this->email = $dealer->email;
        $this->phone = $dealer->phone;
        $this->address = $dealer->address;
        $this->status = $dealer->status;
        $this->currentImage = $dealer->image;

        $this->openModal();
    }

    public function show($id)
    {
        $this->selectedDealer = Dealer::findOrFail($id);
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->dealerToDelete = $id;
    }

    public function delete()
    {
        Dealer::findOrFail($this->dealerToDelete)->delete();
        $this->confirmingDeletion = false;

        session()->flash('message', 'Dealer Deleted Successfully!');
    }
}
