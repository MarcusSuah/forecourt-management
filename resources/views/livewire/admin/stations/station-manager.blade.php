<div>
    <div class="p-4 max-w-full">

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="fixed top-4 middle-4 z-50 bg-green-500 border border-green-300 text-white dark:bg-green-800 dark:text-white-500 dark:border-green-600 px-4 py-3 rounded-lg shadow-md"
                role="alert">
                {{ session('message') }}
            </div>
        @endif
        <!-- Header with Create button -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Service Stations</h1>
            <button wire:click="create"
                class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-6 mb-2 hover:bg-blue-700">
                + Add New Station
            </button>
        </div>

        <!-- Stations Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
          <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            ID
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            SAP Number
                        </th>

                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Dealer</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Phone</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Logo</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Status</th>
                        <th
                            class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($stations as $station)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                               STA-0 {{ $station->id }}</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $station->sap_number }}</td>

                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $station->name }}</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-300">
                                {{ $station->dealer->fname ?? 'N/A' }} {{ $station->dealer->lname ?? '' }}</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-300">
                                {{ $station->email }}</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-300">
                                {{ $station->phone }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if ($station->logo)
                                    <img src="{{ asset('storage/' . $station->logo) }}" alt="{{ $station->name }} Logo"
                                        class="w-10 h-10 rounded-md object-cover">
                                @else
                                    <div
                                        class="w-10 h-10 bg-gray-200 dark:bg-gray-900 rounded-md flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs">
                                        No Logo</div>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-center">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                {{ $station->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $station->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-center space-x-2 text-sm">
                                <button wire:click="show({{ $station->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                    title="Show Details">
                                    <!-- Eye icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button wire:click="edit({{ $station->id }})"
                                    class="text-yellow-600 hover:text-yellow-900 focus:outline-none"
                                    title="Edit Station">
                                    <!-- Pencil icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6-6 3.536 3.536-6 6H9v-3.536z" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $station->id }})"
                                    class="text-red-600 hover:text-red-900 focus:outline-none" title="Delete Station">
                                    <!-- Trash icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7L5 7M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">No service
                                stations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 overflow-auto"
                @keydown.escape.window="show = false; $wire.closeModal()">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6 relative"
                    @click.away="show = false; $wire.closeModal()" role="dialog" aria-modal="true"
                    aria-labelledby="modal-title">
                    <button @click="show = false; $wire.closeModal()"
                        class="absolute top-3 right-3 text-gray-600 dark:text-gray-300 hover:text-red-500 focus:outline-none"
                        aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 id="modal-title" class="text-xl font-semibold mb-4 mt-8 text-gray-800 dark:text-gray-100">
                        {{ $stationId ? 'Edit Service Station' : 'Add Service Station' }}
                    </h2>

                    <form wire:submit.prevent="store" class="space-y-4 mt-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="territory_manager"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Territory
                                    Manager</label>
                                <input id="territory_manager" type="text" wire:model.defer="territory_manager"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2"
                                    placeholder="Optional" />
                                @error('territory_manager')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="dealer_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dealer</label>
                                <select id="dealer_id" wire:model.defer="dealer_id"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2">
                                    <option value="">-- Select Dealer --</option>
                                    @foreach ($dealers as $dealer)
                                        <option value="{{ $dealer->id }}">{{ $dealer->fname }}
                                            {{ $dealer->lname }}</option>
                                    @endforeach
                                </select>
                                @error('dealer_id')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Station
                                    Name</label>
                                <input id="name" type="text" wire:model.defer="name"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('name')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Station
                                    Email</label>
                                <input id="email" type="email" wire:model.defer="email"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('email')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Station
                                    Phone</label>
                                <input id="phone" type="text" wire:model.defer="phone"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('phone')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="location"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Station
                                    Location</label>
                                <input id="location" type="text" wire:model.defer="location"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('location')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="sap_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">SAP
                                    Number</label>
                                <input id="sap_number" type="text" wire:model.defer="sap_number"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('sap_number')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="opening_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening
                                    Time</label>
                                <input id="opening_time" type="time" wire:model.defer="opening_time"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('opening_time')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="closing_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Closing
                                    Time</label>
                                <input id="closing_time" type="time" wire:model.defer="closing_time"
                                    class="mt-1 block w-full rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2" />
                                @error('closing_time')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex flex-col justify-between">
                                <label for="is_active"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                                <input id="is_active" type="checkbox" wire:model.defer="is_active"
                                    class="mt-1 h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" />
                                @error('is_active')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                                <input type="file" wire:model="logo"
                                    class="mt-1 block w-full rounded-md  dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2" />
                                @error('logo')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror

                                <div class="mt-2">
                                    @if ($logoPreview)
                                        <img src="{{ $logoPreview }}" alt="Logo Preview"
                                            class="h-20 w-20 rounded-md object-cover" />
                                    @elseif ($currentLogo)
                                        <img src="{{ asset('storage/' . $currentLogo) }}" alt="Current Logo"
                                            class="h-20 w-20 rounded-md object-cover" />
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" @click="show = false; $wire.closeModal()"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Show Modal --}}
        @if ($isShowOpen && $showStation)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
                @keydown.escape.window="show = false; $wire.closeModal()">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 relative"
                    @click.away="show = false; $wire.closeModal()" role="dialog" aria-modal="true">
                    <button @click="show = false; $wire.closeModal()"
                        class="absolute top-3 right-3 text-gray-600 dark:text-gray-300 hover:text-red-500 focus:outline-none"
                        aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">{{ $showStation->name }}
                    </h2>

                    <div class="flex space-x-6">
                        <div class="flex-shrink-0">
                            @if ($showStation->logo)
                                <img src="{{ asset('storage/' . $showStation->logo) }}"
                                    alt="{{ $showStation->name }} Logo"
                                    class="h-28 w-28 rounded-md object-cover border border-gray-300" />
                            @else
                                <div
                                    class="h-28 w-28 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    No Logo</div>
                            @endif
                        </div>
                        <div class="flex-grow text-gray-700 dark:text-gray-300">
                            <p><span class="font-semibold">Territory Manager:</span>
                                {{ $showStation->territory_manager ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Dealer:</span> {{ $showStation->dealer->fname ?? 'N/A' }}
                                {{ $showStation->dealer->lname ?? '' }}</p>
                            <p><span class="font-semibold">Email:</span> {{ $showStation->email }}</p>
                            <p><span class="font-semibold">Phone:</span> {{ $showStation->phone }}</p>
                            <p><span class="font-semibold">Location:</span> {{ $showStation->location }}</p>
                            <p><span class="font-semibold">SAP Number:</span> {{ $showStation->sap_number }}</p>
                            <p><span class="font-semibold">Opening Time:</span>
                                {{ \Carbon\Carbon::parse($showStation->opening_time)->format('h:i A') }}</p>
                            <p><span class="font-semibold">Closing Time:</span>
                                {{ \Carbon\Carbon::parse($showStation->closing_time)->format('h:i A') }}</p>
                            <p>
                                <span class="font-semibold">Status:</span>
                                <span
                                    class="px-2 py-1 rounded-full text-xs
                                {{ $showStation->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $showStation->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><span class="font-semibold">Created At:</span>
                                {{ $showStation->created_at->format('d M Y') }}</p>
                            <p><span class="font-semibold">Updated At:</span>
                                {{ $showStation->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($isDeleteOpen)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
                @keydown.escape.window="show = false; $wire.closeModal()">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full p-6 relative"
                    @click.away="show = false; $wire.closeModal()" role="dialog" aria-modal="true">
                    <button @click="show = false; $wire.closeModal()"
                        class="absolute top-3 right-3 text-gray-600 dark:text-gray-300 hover:text-red-500 focus:outline-none"
                        aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirm Delete</h3>

                    <p class="text-gray-700 dark:text-gray-300 mb-6">Are you sure you want to delete this service
                        station? This action cannot be undone.</p>

                    <div class="flex justify-end space-x-3">
                        <button @click="show = false; $wire.closeModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none">
                            Cancel
                        </button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>
