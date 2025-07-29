<div>
    <div class="p-6">

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

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Tank Management</h1>
            <button wire:click="openCreateModal"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Create New Tank
            </button>
        </div>

        <div class="mb-4 ">
            <label class="font-semibold">Show Capacity In:</label>
            <select wire:model="displayUnit" class="ml-2 border rounded px-2 py-1 dark:bg-gray-700 dark:text-white">
                <option value="liters">Liters</option>
                <option value="us_gallons">US Gallons</option>
                <option value="imperial_gallons">UK Gallons</option>
            </select>
        </div>
        @php
            $unit = $this->displayUnit;
        @endphp
        {{-- Tanks Table --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Station</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Tank Name</th>
                        <th class="px-4 py-2">Capacity
                            <span title="Liters / US Gallons / UK Gallons">🛢️</span>
                        </th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-100">

                    @forelse ($tanks as $index => $tank)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $tank->station->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $tank->product->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $tank->name }}</td>
                            <td class="px-4 py-2">
                                @if ($unit === 'liters')
                                    {{ number_format($tank->capacity, 2) }} Ltrs
                                @elseif ($unit === 'us_gallons')
                                    {{ number_format($tank->capacity_in_us_gallons, 2) }} US Gal
                                @else
                                    {{ number_format($tank->capacity_in_imperial_gallons, 2) }} UK Gal
                                @endif
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 rounded text-l {{ $tank->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($tank->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="show({{ $tank->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                    View
                                </button>
                                <button wire:click="edit({{ $tank->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-cyan-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-300 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $tank->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 text-xs font-semibold rounded hover:bg-red-200 dark:hover:bg-red-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 4a1 1 0 00-1 1v1H3.5a.5.5 0 000 1h.682l.717 9.037A2 2 0 006.893 18h6.215a2 2 0 001.994-1.963L15.5 7h.682a.5.5 0 000-1H15V5a1 1 0 00-1-1H6zm2 3a.5.5 0 011 0v7a.5.5 0 01-1 0V7zm4 0a.5.5 0 011 0v7a.5.5 0 01-1 0V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 dark:bg-gray-800  text-gray-500">No Tanks found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-4">
            {{ $tanks->links() }}
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-lg mx-auto mt-10 shadow-lg overflow-y-auto max-h-[90vh] p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                        {{ $tankId ? 'Edit Tank' : 'Add Multiple Tanks' }}
                    </h2>

                    <form wire:submit.prevent="store">
                        <!-- Station -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Station</label>
                            <select wire:model.defer="station_id"
                                class="w-full border px-3 py-2 rounded text-gray-800 dark:text-gray-900">
                                <option value="">-- Select Station --</option>
                                @foreach ($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->name }}</option>
                                @endforeach
                            </select>
                            @error('station_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
                            <select wire:model="product_id"
                                class="w-full border px-3 py-2 rounded text-gray-800 dark:text-gray-900">
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>



                        @if (!$tankId)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Number of Tank to Generate
                                </label>
                                <input type="number" wire:model="nameInput"
                                    class="w-full border px-3 py-2 rounded text-gray-800 dark:text-gray-900"
                                    placeholder="e.g. 5" />
                                <p class="text-sm text-gray-500 mt-1">System will auto-generate tankss like PMS-TANK-1,
                                    PMS-TANK-2, etc.</p>
                                @error('nameInput')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif


                        <!-- Capacity Input -->
                        <div class="mb-4">
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                            <div class="flex space-x-2">
                                <input type="number" wire:model.defer="capacity" id="capacity"
                                    class="mt-1 block w-full rounded-md text-gray-800 dark:text-gray-900 border-gray-300 shadow-sm"
                                    min="0" step="0.01" placeholder="e.g. 10000" />
                                <select wire:model="capacity_unit"
                                    class="mt-1 block rounded-md border-gray-300 shadow-sm text-gray-800 dark:text-gray-900"">
                                    <option value="liters">Liters</option>
                                    <option value="gallons">Gallons</option>
                                </select>
                            </div>
                            @error('capacity')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select wire:model.defer="status"
                                class="w-full border px-3 py-2 rounded text-gray-800 dark:text-gray-900">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-800">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Show Modal --}}
        @if ($isShowOpen && $selectedTank)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Tank Details</h2>
                    <div class="space-y-2">
                        <p><strong>Station:</strong> {{ $selectedTank->station->name ?? '-' }}</p>
                        <p><strong>Product:</strong> {{ $selectedTank->product->name ?? '-' }}</p>
                        <p><strong>Name:</strong> {{ $selectedTank->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($selectedTank->status) }}</p>
                        <p><strong>Capacity:</strong></p>
                        <ul class="ml-4 space-y-1">
                            <li><span title="Liters">🛢️ {{ number_format($selectedTank->capacity, 2) }} L</span></li>
                            <li><span title="US Gallons">🇺🇸
                                    {{ number_format($selectedTank->capacity_in_us_gallons, 2) }} Gal</span></li>
                            <li><span title="Imperial Gallons">🇬🇧
                                    {{ number_format($selectedTank->capacity_in_imperial_gallons, 2) }} Gal</span></li>
                        </ul>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($isDeleteOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Delete Confirmation</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">Are you sure you want to delete this Tank?</p>
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
