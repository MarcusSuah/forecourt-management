<div>
    <div class="p-6">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="fixed top-4 middle-4 z-50 bg-green-500 border border-green-300 text-white px-4 py-3 rounded-lg shadow-md"
                role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Exchange Rates</h1>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Exchange Rates</h1>
            <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create Exchange Rate
            </button>
        </div>

        <div class="overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700 border">
                <thead class="bg-gray-50 uppercase dark:bg-gray-400 text-xs font-semibold text-gray-600">
                    <tr>
                        <th class="px-6 py-3">Base Currency</th>
                        <th class="px-6 py-3">Target Currency</th>
                        <th class="px-6 py-3">Rate</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exchangeRates as $rate)
                        <tr class="bg-white border-b  hover:bg-gray-50 dark:bg-gray-100">
                            <td class="px-6 py-4">
                                {{ $rate->baseCurrency->name }} ({{ $rate->baseCurrency->code }})
                            </td>
                            <td class="px-6 py-4">
                                {{ $rate->targetCurrency->name }} ({{ $rate->targetCurrency->code }})
                            </td>
                            <td class="px-6 py-4">{{ number_format($rate->rate, 2) }}</td>
                            <td class="px-6 py-4">{{ $rate->date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded text-xs font-semibold
                            {{ $rate->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($rate->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="openEditModal({{ $rate->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>

                                    Edit</button>
                                <button wire:click="confirmDelete({{ $rate->id }})"
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
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No exchange rates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        {{ $isEdit ? 'Edit Currency' : 'Create Currency' }}
                    </h2>
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:bg-gray-900">Exchange Rate to USD</label>
                            <input type="text" wire:model="modalRate" class="w-full border rounded px-3 py-2">
                            @error('modalRate')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif --}}

        @if ($isOpen && $modalCurrency)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm p-6 relative">

                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        Update Rate for {{ $modalCurrency->name }} ({{ $modalCurrency->code }})
                    </h2>

                    <div class="mb-4">
                        <label for="modalRate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Rate (against USD)
                        </label>
                        <input type="number" wire:model.defer="modalRate" step="0.0001"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        @error('modalRate')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('isOpen', false)"
                            class="px-4 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Cancel
                        </button>

                        <button wire:click="updateRate"
                            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                            Save
                        </button>
                    </div>

                    <button wire:click="$set('isOpen', false)"
                        class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 dark:hover:text-white">
                        &times;
                    </button>
                </div>
            </div>
        @endif



    </div>

</div>
