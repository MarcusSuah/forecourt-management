<div>
    <div class="p-4 space-y-4">
        {{-- Flash Message --}}
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


        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Dealers</h1>
            <button wire:click="create"
                class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-6 mb-2 hover:bg-blue-700">
                + Add New Dealer
            </button>
        </div>

        {{-- Search --}}
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search dealers..."
            class="w-80 px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-600">

        {{-- Table --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Email
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Phone
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status
                        </th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($dealers as $dealer)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $dealer->fname }}
                                {{ $dealer->lname }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $dealer->email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $dealer->phone }}</td>
                            <td>
                                <span
                                    class="px-2 py-1 text-sm rounded-full
                                            @if ($dealer->status == 'Active') bg-green-100 text-green-600
                                            @elseif($dealer->status == 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($dealer->status == 'Suspended') bg-red-100 text-red-800
                                            @elseif($dealer->status == 'Terminated') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                    {{ $dealer->status }}
                                </span>
                            </td>


                            <td
                                class="px-6 py-4 text-right space-y-2 md:space-y-0 md:space-x-2 flex md:table-cell flex-col md:flex-row md:justify-end">
                                <button wire:click="show({{ $dealer->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                    Show
                                </button>

                                <button wire:click="edit({{ $dealer->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>
                                    Edit
                                </button>

                                <button wire:click="confirmDelete({{ $dealer->id }})"
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
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">No dealers
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">{{ $dealers->links() }}</div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-lg shadow-lg relative p-6">
                    <button @click="show = false; $wire.closeModal()"
                        class="absolute top-2 right-2 text-gray-600 dark:text-gray-300 hover:text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                        {{ $dealerId ? 'Edit Dealer' : 'Add Dealer' }}
                    </h2>

                    <form wire:submit.prevent="store" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">First Name</label>
                                <input type="text" wire:model.defer="fname"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @error('fname')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Last Name</label>
                                <input type="text" wire:model.defer="lname"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @error('lname')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" wire:model.defer="email"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @error('email')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Phone</label>
                                <input type="text" wire:model.defer="phone"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @error('phone')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Date of Birth</label>
                                <input type="date" wire:model.defer="dob"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                @error('dob')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Gender</label>
                                <select wire:model.defer="gender"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <option value="">-- Select --</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Address</label>
                            <textarea wire:model.defer="address"
                                class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                            @error('address')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex space-x-2 items-center">
                            <input type="file" wire:model="image" class="p-2 rounded" />
                            @error('image')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror

                            @if ($imagePreview)
                                <img src="{{ $imagePreview }}" class="w-12 h-12 rounded-full object-cover" />
                            @elseif ($currentImage)
                                <img src="{{ asset('storage/' . $currentImage) }}"
                                    class="w-12 h-12 rounded-full object-cover" />
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                            <select wire:model.defer="status"
                                class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                <option value="Pending">Pending</option>
                                <option value="Active">Active</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                            @error('status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        @endif


        @if ($showModal && $selectedDealer)
            <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">

                    <div class="border rounded-xl overflow-hidden bg-white shadow-lg p-6">
                        <!-- Header -->
                        <div class="p-4 border-b flex items-center justify-between bg-gray-100">
                            <span class="text-sm font-medium text-gray-700">Dealer ID:
                                DLR-0{{ $selectedDealer->id }}</span>
                            <span
                                class="inline-block text-xs px-3 py-1 rounded-full
                                {{ match ($selectedDealer->status) {
                                    'Active' => 'bg-green-100 text-green-700',
                                    'Pending' => 'bg-yellow-100 text-yellow-700',
                                    'Suspended' => 'bg-orange-100 text-orange-700',
                                    'Terminated' => 'bg-red-100 text-red-700',
                                } }}">
                                {{ $selectedDealer->status }}
                            </span>
                        </div>

                        <!-- Profile -->
                        <div class="p-6 text-center">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-blue-500">
                                @if ($selectedDealer->image)
                                    <img src="{{ asset('storage/' . $selectedDealer->image) }}" alt="Profile"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <h3 class="text-lg font-semibold mb-1">
                                <span class="font-medium text-gray-700">Name:</span>
                                {{ $selectedDealer->fname }} {{ $selectedDealer->lname }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-medium text-gray-700">Email:</span>
                                {{ $selectedDealer->email }}
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-medium text-gray-700">Phone:</span>
                                {{ $selectedDealer->phone }}
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-medium text-gray-700">Gender:</span>
                                {{ ucfirst($selectedDealer->gender) }}
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-medium text-gray-700">Date of Birth:</span>
                                {{ \Carbon\Carbon::parse($selectedDealer->dob)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-gray-700">Address:</span>
                                {{ $selectedDealer->address }}
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-4 py-3 text-left text-sm text-gray-500">
                            <div>
                                <span class="font-medium">Created At:</span>
                                {{ $selectedDealer->created_at->format('d M Y') }}
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button wire:click="closeModal"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Close
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @endif


        {{-- Confirm Delete Modal --}}
        @if ($confirmingDeletion)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirm Deletion</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Are you sure you want to delete this dealer?</p>
                    <div class="flex justify-end space-x-2">
                        <button wire:click="$set('confirmingDeletion', false)"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
