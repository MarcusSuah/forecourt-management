<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public $name,
        $email,
        $username,
        $password,
        $phone,
        $status = 'Pending',
        $userId;
    public $avatar, $avatarPreview, $currentAvatar;

    public $isOpen = false;
    public $confirmingDeletion = false;
    public $userToDelete;
    public $showModal = false;
    public $selectedUser;
    public $search = '';
    public $statusFilter = '';

    public $statusOptions = ['Active', 'Pending', 'Suspended', 'Approved'];

    public function render()
    {
        $users = User::query()->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))->latest()->paginate(5);

        return view('livewire.admin.users.user-manager', [
            'users' => $users,
        ]);
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
        $this->selectedUser = null;
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->phone = '';
        $this->status = 'Pending';
        $this->userId = null;
        $this->avatar = null;
        $this->avatarPreview = null;
        $this->currentAvatar = null;
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'nullable|image|max:1024',
        ]);

        if ($this->avatar) {
            $this->avatarPreview = $this->avatar->temporaryUrl();
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'username' => 'required|string|unique:users,username,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string',
            'status' => 'required|in:Pending,Active,Suspended,Approved',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->avatar) {
            $data['avatar'] = $this->avatar->store('avatars', 'public');
        }

        User::updateOrCreate(['id' => $this->userId], $data);

       session()->flash('message', $this->userId ? 'User Updated Successfully!' : 'User Created Successfully!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->status = $user->status;
        $this->currentAvatar = $user->avatar;

        session()->flash('message', $this->userId ? 'User Updated Successfully!' : 'User Created Successfully!');

        $this->openModal();
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->avatarPreview = null;
    }

    public function show($id)
    {
        $this->resetValidation();

        $user = User::findOrFail($id);

        $this->selectedUser = User::findOrFail($id);
        $this->showModal = true;

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->status = $user->status;

        $this->currentAvatar = $user->avatar;
        $this->avatarPreview = null;

        $this->isOpen = true;
    }
    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->userToDelete = $id;
    }

    public function delete()
    {
        User::findOrFail($this->userToDelete)->delete();
        $this->confirmingDeletion = false;

         session()->flash('message', 'User Deleted Successfully!');
    }
}
