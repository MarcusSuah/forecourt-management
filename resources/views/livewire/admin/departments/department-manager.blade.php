<div>


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

    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Departments</h2>

            <button wire:click="create"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">+
                Add New Department</button>

        </div>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">#</th>
                        <th class="px-4 py-2 text-left font-medium">Name</th>
                        <th class="px-4 py-2 text-left font-medium">Status</th>
                        <th class="px-4 py-2 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($departments as $department)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">
                            <td class="px-4 py-2">DPT-0{{ $department->id }}</td>
                            <td class="px-4 py-2">{{ $department->name }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded font-medium {{ $department->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($department->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="show({{ $department->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>

                                    Show</button>
                                <button wire:click="edit({{ $department->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>

                                    Edit</button>
                                <button wire:click="confirmDelete({{ $department->id }})"
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
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">No departments found.</td>
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
                        {{ $departmentId ? 'Edit' : 'Add' }} Department</h2>
                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Name</label>
                            <input type="text" wire:model.defer="name"
                                class="w-full px-3 py-2 text-gray-600 dark:text-gray-900 border rounded @error('name') border-red-500 @enderror">
                            @error('name')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm mb-1">Status</label>
                            <select wire:model.defer="status"
                                class="w-full px-3 py-2 text-gray-600 dark:text-gray-900 border rounded @error('status') border-red-500 @enderror">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300  text-black rounded hover:bg-gray-400 mr-2">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Show Modal --}}
        @if ($isShowOpen && $selectedDepartment)
            <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full dark:bg-gray-900 max-w-sm p-6 rounded shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Department Details</h3>
                    <p><strong>Name:</strong> {{ $selectedDepartment->name }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($selectedDepartment->status) }}</p>
                    <div class="mt-4 text-right">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Close</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($isDeleteOpen)
            <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full dark:bg-gray-900 max-w-sm p-6 rounded shadow text-center">
                    <p class="mb-4">Are you sure you want to delete this department?</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Yes</button>
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">No</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
