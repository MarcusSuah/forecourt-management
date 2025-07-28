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
            <h2 class="text-xl font-bold">Employees Management</h2>
            <button wire:click="create"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                + Add New Employee
            </button>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="hidden md:table-header-group text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Profile Pic</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Station</th>
                        <th class="px-4 py-3">Designation</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Gender</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">

                            <td class="px-4 py-2">EMP-0{{ $employee->id }}</td>
                            <td class="px-4 py-2">
                                @if ($employee->image)
                                    <img src="{{ asset('storage/' . $employee->image) }}"
                                        class="w-12 h-12 object-cover rounded-full">
                                @else
                                    <span class="text-gray-400 italic">No image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $employee->fname }} {{ $employee->lname }}</td>
                            <td class="px-4 py-2">{{ $employee->station->name }}</td>
                            <td class="px-4 py-2">{{ $employee->designation->name }}</td>
                            <td class="px-4 py-2">{{ $employee->phone }}</td>
                            <td class="px-4 py-2 capitalize">{{ $employee->gender }}</td>
                            <td class="px-4 py-2 flex space-x-2">
                                <button wire:click="show({{ $employee->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-semibold rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 3.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM10 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                    Show
                                </button>
                                <button wire:click="edit({{ $employee->id }})"
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 text-xs font-semibold rounded hover:bg-yellow-200 dark:hover:bg-yellow-600 transition w-full md:w-auto">
                                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $employee->id }})"
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
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 md:table-row block md:table-row mb-4 rounded-md shadow-sm md:shadow-none">

                            <td colspan="12" class="text-center text-gray-300 py-4">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create/Edit Modal --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl w-full max-w-2xl mx-auto mt-10 shadow-lg overflow-y-auto max-h-[90vh] p-6">

                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-300 mt-5">
                        {{ $employeeId ? 'Edit' : 'Add' }} Employee</h3>
                        <form wire:submit.prevent="store">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                <div class="mb-2">
                                    <label class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">First
                                        Name</label>
                                    <input wire:model.defer="fname" type="text"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('fname')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Last
                                        Name</label>
                                    <input wire:model.defer="lname" type="text"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('lname')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Email</label>
                                    <input wire:model.defer="email" type="email"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('lname')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Phone</label>
                                    <input type="text" wire:model.defer="phone"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('lname')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Socurity
                                        Security Number</label>
                                    <input type="text" wire:model.defer="ssn"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('ssn')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Date
                                        of Birth</label>
                                    <input type="date" wire:model.defer="dob"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('dob')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="blocktext-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Gender</label>
                                    <select wire:model.defer="gender"
                                        class="w-full px-3 py-2 text-gray-600 dark:text-gray-900 border rounded @error('status') border-red-500 @enderror">
                                        <option value="">-- Select --</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Employment
                                        Date</label>
                                    <input type="date" wire:model.defer="emp_date"
                                        class="w-full text-gray-600 dark:text-gray-900 border rounded px-3 py-2 focus:outline-blue-500" />
                                    @error('emp_date')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Service
                                        Station</label>
                                    <select wire:model.defer="station_id"
                                        class="w-full px-3 py-2 text-gray-600 dark:text-gray-900 border rounded @error('status') border-red-500 @enderror">
                                        <option value="">-- Select Station --</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('station_id')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label
                                        class="block text-lg font-medium mb-1 text-gray-800 dark:text-gray-300">Position</label>
                                    <select wire:model.defer="designation_id"
                                        class="w-full px-3 py-2 text-gray-600 dark:text-gray-900 border rounded @error('status') border-red-500 @enderror">
                                        <option value="">-- Select Station --</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('designation_id')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="block text-lg text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                <textarea wire:model.defer="address"
                                    class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                                @error('address')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-x-2 items-center">
                                <label class="block text-lg text-gray-700 dark:text-gray-300 mb-1">Employee
                                    Photo</label>
                                <input type="file" wire:model="image" class="input">
                                @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                    <img src="{{ $image->temporaryUrl() }}"
                                        class="mt-2 w-24 h-24 object-cover rounded" />
                                @elseif (is_string($image))
                                    <img src="{{ asset('storage/' . $image) }}"
                                        class="mt-2 w-24 h-24 object-cover rounded" />
                                @endif
                                @error('image')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 mr-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                            </div>
                        </form>
                </div>
            </div>
        @endif

        @if ($isShowOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl w-full max-w-xl mx-auto mt-10 shadow-lg overflow-y-auto max-h-[90vh] p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-rose-600 text-center">Employee Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                        <div><strong>Name:</strong> {{ $selectedEmployee->fname }} {{ $selectedEmployee->lname }}
                        </div>
                        <div><strong>Email:</strong> {{ $selectedEmployee->email }}</div>
                        <div><strong>Phone:</strong> {{ $selectedEmployee->phone }}</div>
                        <div><strong>SSN:</strong> {{ $selectedEmployee->ssn }}</div>
                        <div><strong>Gender:</strong> {{ ucfirst($selectedEmployee->gender) }}</div>
                        <div><strong>DOB:</strong> {{ $selectedEmployee->dob }}</div>
                        <div><strong>Employment Date:</strong> {{ $selectedEmployee->emp_date }}</div>
                        <div><strong>Address:</strong> {{ $selectedEmployee->address }}</div>
                        <div><strong>Station:</strong> {{ $selectedEmployee->station->name ?? '—' }}</div>
                        <div><strong>Position:</strong> {{ $selectedEmployee->designation->name ?? '—' }}</div>

                        <div class="md:col-span-2 mt-4">
                            <strong>Photo:</strong><br>
                            @if ($selectedEmployee->image)
                                <img src="{{ asset('storage/' . $selectedEmployee->image) }}"
                                    class="mt-2 w-24 h-24 object-cover rounded" />
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-400 text-black rounded hover:bg-gray-600">Close</button>
                    </div>
                </div>
            </div>
        @endif


        {{-- Delete Confirmation Modal --}}
        @if ($isDeleteOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black  bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 p-6 rounded shadow w-full max-w-sm text-center">
                    <p class="mb-4">Are you sure you want to delete this employee?</p>
                    <div class="flex justify-center space-x-4  ">
                        <button wire:click="deleteEmployee"
                            class="px-4 py-2 bg-red-600 text-white rounded">Yes</button>
                        <button wire:click="$set('isDeleteOpen', false)"
                            class="px-4 py-2 bg-gray-400 rounded">No</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .input {
                @apply w-full border px-3 py-2 rounded focus: outline-none focus:ring;
            }
        </style>
    @endpush

</div>
