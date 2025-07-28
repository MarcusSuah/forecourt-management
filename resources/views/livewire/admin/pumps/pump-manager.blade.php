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
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Pumps</h2>
            <button wire:click="openCreateModal"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Create New Pump
            </button>
        </div>


        {{-- Pumps Table --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Station</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Pump Name</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-100">

                    @forelse ($pumps as $index => $pump)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">

                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $pump->station->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $pump->product->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $pump->name }}</td>
                            <td class="px-4 py-2 capitalize">{{ $pump->status }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <button wire:click="show({{ $pump->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                    View
                                </button>
                                <button wire:click="edit({{ $pump->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-cyan-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-300 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $pump->id }})"
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
                            <td colspan="6" class="text-center py-4 dark:bg-gray-800  text-gray-500">No pumps found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-4">
            {{ $pumps->links() }}
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-lg mx-auto mt-10 shadow-lg overflow-y-auto max-h-[90vh] p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                        {{ $pumpId ? 'Edit Pump' : 'Add Multiple Pumps' }}
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



                        @if (!$pumpId)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Number of Pumps to Generate
                                </label>
                                <input type="number" wire:model="nameInput"
                                    class="w-full border px-3 py-2 rounded text-gray-800 dark:text-gray-900"
                                    placeholder="e.g. 5" />
                                <p class="text-sm text-gray-500 mt-1">System will auto-generate pumps like PMS-PUMP-1,
                                    PMS-PUMP-2, etc.</p>
                                @error('nameInput')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif


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
        @if ($isShowOpen && $selectedPump)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Pump Details</h2>
                    <div class="space-y-2">
                        <p><strong>Station:</strong> {{ $selectedPump->station->name ?? '-' }}</p>
                        <p><strong>Product:</strong> {{ $selectedPump->product->name ?? '-' }}</p>
                        <p><strong>Name:</strong> {{ $selectedPump->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($selectedPump->status) }}</p>
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
                    <p class="mb-4 text-gray-700 dark:text-gray-300">Are you sure you want to delete this pump?</p>
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
