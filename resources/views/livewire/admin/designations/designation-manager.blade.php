<div>
    <div class="p-4">

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
            <h1 class="text-xl font-bold">Designations</h1>
            <button wire:click="openModal"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Add New Position
            </button>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">#</th>
                        <th class="py-3 px-4">Department</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($designations as $designation)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">
                            <td class="py-2 px-4">DSG-0{{ $designation->id }}</td>
                            <td class="py-2 px-4">{{ $designation->department->name }}</td>
                            <td class="py-2 px-4">{{ $designation->name }}</td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $designation->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($designation->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-right space-x-2">
                                <button wire:click="show({{ $designation->id }})"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 text-xs rounded">
                                    Show
                                </button>
                                <button wire:click="edit({{ $designation->id }})"
                                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-2 py-1 text-xs rounded">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $designation->id }})"
                                    class="bg-red-100 hover:bg-red-200 text-red-800 px-2 py-1 text-xs rounded">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No designations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-600">
                        {{ $designationId ? 'Edit' : 'Add' }} Designation</h2>
                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block mb-1">Department</label>
                            <select wire:model.defer="department_id"
                                class="w-full border px-3 py-2  text-gray-600 dark:text-gray-900 rounded @error('department_id') border-red-500 @enderror">
                                <option value="">-- Select Department --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Designation Name</label>
                            <input type="text" wire:model.defer="name"
                                class="w-full border px-3 py-2  text-gray-600 dark:text-gray-900 rounded @error('name') border-red-500 @enderror">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Status</label>
                            <select wire:model.defer="status"
                                class="w-full border px-3 py-2  text-gray-600 dark:text-gray-900 rounded @error('status') border-red-500 @enderror">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-black rounded me-2">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Show Modal --}}
        @if ($isShowOpen && $selectedDesignation)
            <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full dark:bg-gray-900 max-w-sm p-6 rounded shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Designation Details</h3>
                    <p><strong>Department:</strong> {{ $selectedDesignation->department->name }}</p>
                    <p><strong>Name:</strong> {{ $selectedDesignation->name }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($selectedDesignation->status) }}</p>
                    <div class="mt-4 text-right">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Close</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($isDeleteOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full max-w-sm p-6 rounded shadow text-center">
                    <p class="mb-4">Are you sure you want to delete this designation?</p>
                    <div class="flex justify-center space-x-4">
                        <button wire:click="deleteDesignation"
                            class="px-4 py-2 bg-red-600 text-white rounded">Yes</button>
                        <button wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded">No</button>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>
