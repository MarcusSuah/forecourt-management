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

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Unit Price Management</h1>

            <button wire:click="create"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Add Unit Price
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" wire:model.debounce.300ms="search"
                        placeholder="Search by product or station..."
                        class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" wire:model="dateFilter"
                        class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Station</label>
                    <select wire:model="stationFilter"
                        class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Stations</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
                    <select wire:model="productFilter"
                        class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('date')">
                            Date
                            @if ($sortField === 'date')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th class="px-6 py-3">Station</th>
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('price')">
                            Price
                            @if ($sortField === 'price')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unitPrices as $unitPrice)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block mb-4 rounded-md shadow-sm md:shadow-none">
                            <td class="px-6 py-4 md:table-cell block">
                                <span class="md:hidden font-semibold">Date:</span>
                                {{ $unitPrice->date }}
                            </td>
                            <td class="px-6 py-4 md:table-cell block">
                                <span class="md:hidden font-semibold">Station:</span>
                                {{ $unitPrice->station->name }}
                            </td>
                            <td class="px-6 py-4 md:table-cell block">
                                <span class="md:hidden font-semibold">Product:</span>
                                {{ $unitPrice->product->name }}
                            </td>
                            <td class="px-6 py-4 md:table-cell block">
                                <span class="md:hidden font-semibold">Price:</span>
                                {{ number_format($unitPrice->price, 2) }}
                            </td>
                            <td
                                class="px-6 py-4 flex md:table-cell flex-col md:flex-row md:justify-end space-y-2 md:space-y-0 md:space-x-2">
                                <button wire:click="show({{ $unitPrice->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                    Show
                                </button>
                                <button wire:click="edit({{ $unitPrice->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $unitPrice->id }})"
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
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No unit prices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $unitPrices->links() }}
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        {{ $unitPriceId ? 'Edit Unit Price' : 'Create Unit Price' }}
                    </h2>
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service
                                Station</label>
                            <select wire:model="station_id"
                                class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Service Station</option>
                                @foreach ($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->name }}</option>
                                @endforeach
                            </select>
                            @error('station_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
                            <select wire:model="product_id"
                                class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                            <input type="number" step="0.0001" wire:model="price"
                                class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                            <input type="date" wire:model="date"
                                class="w-full border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Show Modal --}}
        @if ($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        Unit Price Details
                    </h2>

                    <div class="space-y-3 text-gray-800 dark:text-gray-100">
                        <div><strong>ID:</strong> {{ $showData['id'] }}</div>
                        <div><strong>Service Station:</strong> {{ $showData['station'] }}</div>
                        <div><strong>Product:</strong> {{ $showData['product'] }}</div>
                        <div><strong>Price:</strong> {{ $showData['price'] }}</div>
                        <div><strong>Date:</strong> {{ $showData['date'] }}</div>
                        <div><strong>Created:</strong> {{ $showData['created_at'] }}</div>
                        <div><strong>Updated:</strong> {{ $showData['updated_at'] }}</div>
                    </div>

                    <div class="mt-6 text-right">
                        <button wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white">Close</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($deleteModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        Confirm Deletion
                    </h2>
                    <p class="mb-6 text-gray-700 dark:text-gray-300">Are you sure you want to delete this unit price?
                        This action cannot be undone.</p>
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="$set('deleteModal', false)"
                            class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white">Cancel</button>
                        <button type="button" wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
