<div>
    <div class="p-6">
        <!-- Flash Message -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                class="fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-3 rounded-lg shadow-md">
                {{ session('message') }}
            </div>
        @endif

        <!-- Header and Create Button -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Meter Collection Management</h1>
            <button wire:click="create"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Collect Meter
            </button>
        </div>

        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4" x-data="{ showFilters: false }">
            <!-- Filter Toggle -->
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v4.586a1 1 0 01-.293.707L12 19.414V14.121a1 1 0 00-.293-.707L5.293 7.586A1 1 0 015 6.879V4z">
                        </path>
                    </svg>
                    <span class="text-lg font-medium text-gray-900 dark:text-gray-100">Filters</span>
                    @if ($dateFilter || $dayFilter || $monthFilter || $yearFilter || $stationFilter)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Active
                        </span>
                    @endif
                </div>
                <button @click="showFilters = !showFilters" class="flex items-center space-x-1 text-sm text-gray-500">
                    <span>Toggle</span>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': showFilters }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Inline Filters -->
            <div x-show="showFilters" x-transition class="flex flex-wrap justify-end items-end gap-4">
                <!-- Date -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1">Date</label>
                    <input type="date" wire:model.live="dateFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                </div>

                <!-- Day -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1 w-20 ">Day</label>
                    <select wire:model.live="dayFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        <option value="">All</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Month -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1">Month</label>
                    <select wire:model.live="monthFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        <option value="">All</option>
                        @foreach ([
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec',
    ] as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Year -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1">Year</label>
                    <select wire:model.live="yearFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        <option value="">All</option>
                        @for ($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Station -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1">Station</label>
                    <select wire:model.live="stationFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        <option value="">All</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="flex flex-col">
                    <label class="font-semibold text-blue-600 dark:text-teal-500 text-sm mb-1">Search</label>
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search..."
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                </div>

                <!-- Clear -->
                <div>
                    <button wire:click="clearFilters"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md">
                        Clear
                    </button>
                </div>
            </div>
        </div>


        <!-- Results Summary -->
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $meterCollections->count() }} of {{ $totalRecords }} records
            @if ($dateFilter || $dayFilter || $monthFilter || $yearFilter || $stationFilter)
                (filtered)
            @endif
        </div>

        <!-- Search -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search collections..."
                class="w-medium border rounded px-3 py-2 focus:outline-blue-500 dark:bg-gray-700 dark:text-white">
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th wire:click="sortBy('date')"
                            class="cursor-pointer px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Date
                            @if ($sortField === 'date')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Station</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Product</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Pump</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Opening Meter</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Closing Meter</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Volume</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            RTT</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Sales (Gallon)</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Turnover</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($meterCollections as $collection)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block mb-4 rounded-md shadow-sm md:shadow-none">
                            <td class="px-6 py-4">{{ $collection->date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">{{ $collection->station->name }}</td>
                            <td class="px-6 py-4">{{ $collection->product->name }}</td>
                            <td class="px-6 py-4">{{ $collection->pump?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ number_format($collection->opening_meter, 0) }}</td>
                            <td class="px-6 py-4">{{ number_format($collection->closing_meter, 0) }}</td>

                            <td class="px-6 py-4">{{ number_format($collection->volume, 0) }} (gal)</td>
                            <td class="px-6 py-4">{{ number_format($collection->rtt, 1) }} (gal)</td>
                            <td class="px-6 py-4">{{ number_format($collection->sales_in_gallon, 1) }} (gal)</td>
                            <td class="px-6 py-4">{{ number_format($collection->sales_turnover, 2) }} USD</td>

                            <td class="px-4 py-2 whitespace-nowrap text-center space-x-2 text-sm">
                                <button wire:click="show({{ $collection->id }})"
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
                                <button wire:click="edit({{ $collection->id }})"
                                    class="text-yellow-600 hover:text-yellow-900 focus:outline-none"
                                    title="Edit Station">
                                    <!-- Pencil icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6-6 3.536 3.536-6 6H9v-3.536z" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $collection->id }})"
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
                            <td colspan="9" class="px-6 py-4 text-center dark:bg-gray-900 text-gray-500">No meter
                                collections found
                            </td>
                        </tr>
                    @endforelse
                </tbody>


                <!-- Totals Row -->
                @if ($meterCollections->isNotEmpty())
                    <tfoot class="bg-gray-100 w-full dark:bg-gray-700 font-semibold">
                        <tr>
                            <td colspan="12" class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                <div class="grid grid-cols-10 gap-0 text-center">
                                    <div class="col-span-4"></div> {{-- Empty space for first 5 columns --}}
                                    <span class="font-semibold  text-rose-500">TOTAL:</span>
                                    <div>
                                        <span class="block font-bold text-yellow-500">Volume</span>
                                        <span>{{ number_format($totals['volume'], 0) }}</span>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-lime-500">RTT</span>
                                        <span>{{ number_format($totals['rtt'], 1) }}</span>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-green-500">Sales (Gallon)</span>
                                        <span>{{ number_format($totals['sales_in_gallon'], 1) }}</span>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-rose-500">Turnover</span>
                                        <span>$ {{ number_format($totals['sales_turnover'], 2) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                @endif



            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $meterCollections->links() }}
        </div>

        <!-- Create/Edit Modal -->
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="bg-white rounded-lg p-6 w-full dark:bg-gray-900 max-w-4xl max-h-[90vh] overflow-y-auto shadow-lg">
                    <h1 class="text-xl font-bold mb-4 text-rose-600 ">
                        {{ $meterCollectionId ? 'Edit Meter Collection' : 'Create Meter Collection' }}
                    </h1>

                    <div x-data="meterCalc($wire)" x-init="opening_meter = {{ $opening_meter }};
                    closing_meter = {{ $closing_meter }};
                    rtt = {{ $rtt }};
                    unit_price_at_sale = {{ $unit_price_at_sale }};
                    calculate();

                    // Watch for edit mode initialization
                    $wire.on('edit-mode', (data) => {
                        opening_meter = data.opening_meter;
                        closing_meter = data.closing_meter;
                        rtt = data.rtt;
                        unit_price_at_sale = data.unit_price_at_sale;
                        calculate();
                    });"
                        @unit-price-updated.window="unit_price_at_sale = $event.detail.price; calculate();">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit.prevent="save">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                                <!-- Date -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Date</label>
                                    <input type="date" wire:model="date"
                                        class="w-full p-2 border rounded-lg dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                                    @error('date')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Station -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Station</label>
                                    <select wire:model="station_id"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                        <option value="">Select Station</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('station_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Shift -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Shift</label>
                                    <select wire:model="shift_id"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                        <option value="">Select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}
                                                ({{ $shift->time_range }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Product -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Product</label>
                                    <select wire:model.live="product_id" @change="fetchUnitPrice($event.target.value)"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Pump -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Pump</label>
                                    <select wire:model="pump_id"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                        <option value="">Select Pump</option>
                                        @foreach ($pumps as $pump)
                                            <option value="{{ $pump->id }}">{{ $pump->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pump_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Opening Meter -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Opening Meter</label>
                                    <input type="number" step="0.01" wire:model="opening_meter"
                                        x-model="opening_meter" @input="calculate()"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                    @error('opening_meter')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Closing Meter -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Closing Meter</label>
                                    <input type="number" step="0.01" wire:model="closing_meter"
                                        x-model="closing_meter" @input="calculate()"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                    @error('closing_meter')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Volume -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Sales Volume</label>
                                    <input type="number" step="0.01" wire:model="volume" x-model="volume"
                                        class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 dark:bg-gray-900 focus:ring-blue-500"
                                        readonly>
                                    @error('volume')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- RTT -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">RTT</label>
                                    <input type="number" step="0.01" wire:model="rtt" x-model="rtt"
                                        @input="calculate()"
                                        class="w-full p-2 border rounded-lg focus:ring-2 dark:bg-gray-900 focus:ring-blue-500">
                                    @error('rtt')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Unit Price -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium text-rose-500">Unit Price</label>
                                    <input type="number" step="0.0001" wire:model="unit_price_at_sale"
                                        x-model="unit_price_at_sale"
                                        class="w-full p-2 border rounded-lg text-rose-400 bg-gray-100 focus:ring-2 dark:bg-gray-900 focus:ring-blue-500"
                                        readonly>
                                    @error('unit_price_at_sale')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>



                                <!-- Sales in Gallon -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Sales (Gallon)</label>
                                    <input type="number" step="0.01" wire:model="sales_in_gallon"
                                        x-model="sales_in_gallon"
                                        class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 dark:bg-gray-900 focus:ring-blue-500"
                                        readonly>
                                    @error('sales_in_gallon')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Sales Turnover -->
                                <div class="mb-4">
                                    <label class="block mb-1 font-medium">Sales Turnover</label>
                                    <input type="number" step="0.01" wire:model="sales_turnover"
                                        x-model="sales_turnover"
                                        class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 dark:bg-gray-900 focus:ring-blue-500"
                                        readonly>
                                    @error('sales_turnover')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" wire:click="$set('isOpen', false)"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg dark:bg-gray-300 hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Show Modal -->
        @if ($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-full dark:bg-gray-900 max-w-2xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-rose-500">Meter Collection Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($showData as $key => $value)
                            <div class="flex justify-between border-b pb-2">
                                <strong>{{ $key }}:</strong>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-lime-400 text-black rounded-lg hover:bg-lime-700">Close</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($deleteModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-red-600">Confirm Deletion</h2>
                    <p class="mb-6 text-gray-700">Are you sure you want to delete this meter collection? This action
                        cannot be undone.</p>

                    <div class="flex justify-end space-x-2">
                        <button wire:click="$set('deleteModal', false)"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Alpine.js Calculation Script --}}
    <script>
        function meterCalc($wire) {
            return {
                opening_meter: 0,
                closing_meter: 0,
                rtt: 0,
                unit_price_at_sale: 0,
                volume: 0,
                sales_in_gallon: 0,
                sales_turnover: 0,

                calculate() {
                    this.volume = (parseFloat(this.closing_meter) || 0) - (parseFloat(this.opening_meter) || 0);
                    this.sales_in_gallon = this.volume - (parseFloat(this.rtt) || 0);
                    this.sales_turnover = this.sales_in_gallon * (parseFloat(this.unit_price_at_sale) || 0);

                    // Update Livewire properties
                    $wire.set('volume', this.volume.toFixed(2));
                    $wire.set('sales_in_gallon', this.sales_in_gallon.toFixed(2));
                    $wire.set('sales_turnover', this.sales_turnover.toFixed(2));
                },

                fetchUnitPrice(productId) {
                    if (!productId) {
                        this.unit_price_at_sale = 0;
                        this.calculate();
                        return;
                    }

                    // Livewire will handle this automatically through updatedProductId()
                    // The unitPriceUpdated event will be dispatched and handled above
                }
            }
        }
    </script>
</div>
