<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-4 right-4 z-50 bg-green-500 border border-green-300 text-white dark:bg-green-800 dark:text-green-500 dark:border-green-600 px-4 py-3 rounded-lg shadow-md"
            role="alert">
            {{ session('message') }}
        </div>
    @endif


    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your users and account') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>


    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model="search" placeholder="Search Users..." class="border p-2 rounded w-1/5" />
        <button wire:click="create"
            class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 hover:bg-blue-700">
            + New User
        </button>
    </div>


    <div class="flex items-center space-x-2 mb-4 sm:mb-0 mt-3">
        <span class="text-sm text-gray-700 dark:text-gray-600">Per page:</span>
        <select wire:model.live="perPage" class="border rounded-lg px-2 py-1 text-sm text-gray-600 dark:text-gray-400">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="overflow-x-auto mt-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Avatar</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Username</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $user->id }}</td>

                        <td class="px-6 py-4">
                            @if ($user->avatar)
                                <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $user->avatar) }}"
                                    alt="{{ $user->name }} avatar">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4">{{ $user->username }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->phone }}</td>

                        <td class="py-2 px-4">
                            <span
                                class="px-2 py-1 text-l rounded-full
                                    @if ($user->status == 'Active') bg-green-100 text-green-600
                                    @elseif($user->status == 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->status == 'Suspended') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                {{ $user->status }}
                            </span>
                        </td>

                        <td
                            class="px-6 py-4 text-right space-y-2 md:space-y-0 md:space-x-2 flex md:table-cell flex-col md:flex-row md:justify-end">
                            <button wire:click="show({{ $user->id }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                                Show
                            </button>

                            <button wire:click="edit({{ $user->id }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                </svg>
                                Edit
                            </button>

                            <button wire:click="confirmDelete({{ $user->id }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 text-xs font-semibold rounded hover:bg-red-200 dark:hover:bg-red-600 transition w-full md:w-auto">
                                <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 4a1 1 0 00-1 1v1H3.5a.5.5 0 000 1h.682l.717 9.037A2 2 0 006.893 18h6.215a2 2 0 001.994-1.963L15.5 7h.682a.5.5 0 000-1H15V5a1 1 0 00-1-1H6zm2 3a.5.5 0 011 0v7a.5.5 0 01-1 0V7zm4 0a.5.5 0 011 0v7a.5.5 0 01-1 0V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="8" class="px-6 py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>


    <!-- Create/Edit Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-data="{ show: true }"
            x-show="show" x-transition>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-2xl mx-4 p-6 relative">
                <button @click="show = false; $wire.closeModal()"
                    class="absolute top-2 right-2 text-red-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Close modal">Close
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-4">{{ $userId ? 'Edit User' : 'Create User' }}</h2>
                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full
                            Name:</label>
                        <input type="text" wire:model="name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="name">
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                        <input type="text" wire:model="username"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="username">
                        @error('username')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" wire:model="email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="email">
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                        <input type="password" wire:model="password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="password">
                        @error('password')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        @if ($userId)
                            <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                        <input type="text" wire:model="phone"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="phone">
                        @error('phone')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="flex space-x-2 items-center">
                        <input type="file" wire:model="avatar" class="p-2  rounded" />
                        @if ($avatarPreview)
                            <img src="{{ $avatarPreview }}" class="w-12 h-12 rounded-full object-cover" />
                        @elseif ($currentAvatar)
                            <img src="{{ asset('storage/' . $currentAvatar) }}"
                                class="w-12 h-12 rounded-full object-cover" />
                        @endif
                    </div>


                    <select wire:model.defer="status" class="w-medium p-2 border rounded">
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>


                    <div class="text-right">
                        <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded">{{ $userId ? 'Update' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif



    <!-- Modal -->
    @if ($showModal && $selectedUser)

        <!-- Styled Show Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">

                <div class="border rounded-xl overflow-hidden  bg-white  shadow-lg  p-6">
                    <div class="p-4 border-b flex items-center justify-between bg-gray-100">
                        <span class="text-sm font-medium text-gray-700">User ID: USR-0{{ $selectedUser->id }}</span>
                        <span
                            class="inline-block text-xs px-3 py-1 rounded-full {{ $selectedUser->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $selectedUser->status }}
                        </span>
                    </div>

                    <div class="p-6 text-center">
                        <!-- Profile Image -->
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-green-500">
                            @if ($user->avatar)
                                <img class="w-40 h-20 rounded-full" src="{{ asset('storage/' . $user->avatar) }}"
                                    alt="{{ $user->name }} avatar" alt="Profile"
                                    class="w-full h-full object-cover">
                            @endif
                        </div>
                        <!-- User Info -->
                        <h3 class="text-lg font-semibold mb-1"> <span class="font-medium text-gray-700">Name:
                            </span>{{ $selectedUser->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2"><span class="font-medium text-gray-700">Email:
                            </span>{{ $selectedUser->email }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium text-gray-700">Phone: </span>
                            {{ $selectedUser->phone }}</p>
                    </div>

                    <!-- Footer Info -->
                    <div class="bg-gray-50 px-4 py-3 text-left text-sm text-gray-500">
                        <div>
                            <span class="font-medium"> Created At:</span>
                            {{ $selectedUser->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button wire:click="closeModal"
                            class="bg-red-600 text-white px-4 py-2 rounded  hover:bg-red-700">Close</button>
                    </div>
                    <!-- Actions -->

                </div>
            </div>
        </div>

    @endif


    <!-- Delete Confirmation Modal -->
    @if ($confirmingDeletion)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40"
            x-data="{ show: true }" x-show="show" x-transition>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p>Are you sure you want to delete this user?</p>
                <div class="mt-4 flex justify-end space-x-2">
                    <button wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded">Yes, Delete</button>
                    <button wire:click="$set('confirmingDeletion', false)"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (message) => {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4CAF50",
                }).showToast();
            });
        });
    </script>



</div>
