<div>
    <div class="p-4" x-data="{ showModal: @entangle('showModal') }">
        <!-- FILTERS -->
        <div class="mb-4 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                <input type="date" wire:model.live="dateFilter" class="form-input dark:bg-gray-800 ">
                <select wire:model.live="dayFilter" class="form-select dark:bg-gray-800 ">
                    <option value="">Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <select wire:model.live="monthFilter" class="form-select dark:bg-gray-800">
                    <option value="">Month</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="yearFilter" class="form-select dark:bg-gray-800">
                    <option value="">Year</option>
                    @for ($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                <select wire:model.live="stationFilter" class="form-select dark:bg-gray-800">
                    <option value="">Station</option>
                    @foreach ($stations as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="shiftFilter" class="form-select dark:bg-gray-800">
                    <option value="">Shift</option>
                    @foreach ($shifts as $sh)
                        <option value="{{ $sh->id }}">{{ $sh->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="tankFilter" class="form-select dark:bg-gray-800">
                    <option value="">Tank</option>
                    @foreach ($tanks as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 flex justify-between items-center">
                <input type="text" wire:model.live="search" placeholder="Search tanks..." class="form-input w-1/3">
                <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add Tank Dipping
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Station</th>
                        <th class="px-4 py-2">Shift</th>
                        <th class="px-4 py-2">Tank</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Opening</th>
                        <th class="px-4 py-2">Closing</th>
                        <th class="px-4 py-2">Sales</th>
                        <th class="px-4 py-2">Variance</th>
                        <th class="px-4 py-2">Tank capacity</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dippings as $d)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $d->date }}</td>
                            <td class="px-4 py-2">{{ $d->station->name }}</td>
                            <td class="px-4 py-2">{{ $d->shift->name }}</td>
                            <td class="px-4 py-2">{{ $d->tank->name }}</td>
                            <td class="px-4 py-2">{{ $d->product->name }}</td>
                            <td class="px-4 py-2">{{ $d->opening_dips }}</td>
                            <td class="px-4 py-2">{{ $d->closing_dips }}</td>
                            <td class="px-4 py-2">{{ $d->tank_sales }}</td>
                            <td class="px-4 py-2">{{ $d->variance }}</td>
                            <td class="px-4 py-2">{{ $d->capacity }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded {{ $d->status_badge['class'] }}">
                                    {{ $d->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="edit({{ $d->id }})" class="text-blue-600">Edit</button>
                                <button wire:click="delete({{ $d->id }})" class="text-red-600">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $dippings->links() }}</div>

        <!-- MODAL -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6"
                @click.away="showModal = false" x-transition>
                <h2 class="text-lg font-bold mb-4">{{ $isEdit ? 'Edit Tank Dipping' : 'New Tank Dipping' }}</h2>

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

                <div class="grid grid-cols-2 gap-4">
                    <input type="date" wire:model.defer="form.date" class="form-input dark:bg-gray-800">
                    <select wire:model.defer="form.station_id" class="form-select dark:bg-gray-800">
                        <option value="">Select Station</option>
                        @foreach ($stations as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.defer="form.shift_id" class="form-select dark:bg-gray-800">
                        <option value="">Select Shift</option>
                        @foreach ($shifts as $sh)
                            <option value="{{ $sh->id }}">{{ $sh->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.defer="form.product_id" class="form-select dark:bg-gray-800">
                        <option value="">Select Product</option>
                        @foreach ($tanks as $t)
                            <option value="{{ $t->product->id }}">{{ $t->product->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.defer="form.tank_id" class="form-select dark:bg-gray-800">
                        <option value="">Select Tank</option>
                        @foreach ($tanks as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" wire:model.defer="form.opening_dips" placeholder="Opening Dips"
                        class="form-input dark:bg-gray-800">
                    <input type="number" wire:model.defer="form.qty_rec" placeholder="Qty Received"
                        class="form-input dark:bg-gray-800">
                    <input type="number" wire:model.defer="form.rtt" placeholder="RTT"
                        class="form-input dark:bg-gray-800">
                    <input type="number" wire:model.defer="form.closing_dips" placeholder="Closing Dips"
                        class="form-input dark:bg-gray-800">
                    <select wire:model.defer="form.pump_sales_id" class="form-select dark:bg-gray-800 ">
                        <option value="">Select Pump Sales</option>
                        @foreach (\App\Models\MeterCollection::all() as $mc)
                            <option value="{{ $mc->id }}">#{{ $mc->id }} - {{ $mc->total_sales }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" wire:model.defer="form.threshold" placeholder="Threshold"
                        class="form-input dark:bg-gray-800">
                    <select wire:model.defer="form.status" class="form-select dark:bg-gray-800">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button @click="showModal = false"
                        class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>
@push('styles')
    <style>
        .form-input,
        .form-select {
            @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300;
        }
    </style>
@endpush
