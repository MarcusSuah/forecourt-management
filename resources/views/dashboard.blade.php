{{-- <x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app> --}}



<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Summary Cards -->
        <div class="grid gap-4 md:grid-cols-4">
            <!-- Total Users -->
            <div
                class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 11a4 4 0 10-6 0m6 0a4 4 0 01-6 0" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Users</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ \App\Models\User::count() }} users</p>
                </div>
            </div>

            <!-- Active Users -->
            <div
                class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-3 bg-green-100 text-green-600 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Active Users</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ \App\Models\User::where('status', 'Active')->count() }} active</p>
                </div>
            </div>

            <!-- Pending Users -->
            <div
                class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pending Users</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ \App\Models\User::where('status', 'Pending')->count() }} pending</p>
                </div>
            </div>

            {{-- Suspended Users --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md flex items-center gap-4">
                <div class="bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Suspended Users</h3>
                    <p class="text-2xl font-bold text-red-500">
                        {{ \App\Models\User::where('status', 'Suspended')->count() }}</p>
                </div>
            </div>


            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Total Products</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\Product::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300">Products</span>
                    </p>
                </div>
            </div>


            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Total Dealers</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\Dealer::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300"> Dealers</span>
                    </p>
                </div>
            </div>


            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Totoal Service Stations</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\ServiceStation::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300"> Stations</span>
                    </p>
                </div>
            </div>



            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Number of Shifts</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\Shift::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300"> Shifts</span>
                    </p>
                </div>
            </div>

            {{-- Recent Employee Table --}}
            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Total Employees</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\Employee::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300"> Employees</span>
                    </p>
                </div>
            </div>

               {{-- Recent Employee Table --}}
            <div
                class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <!-- Icon -->
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-cyan-600 dark:bg-cyan-900 dark:text-red-300">
                    <!-- Box icon (Heroicons - outline) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m16 0l-8 8m8-8l-8-8" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Total Banks</h3>
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ \App\Models\Bank::count() }}
                        <span class="text-base font-medium text-gray-500 dark:text-gray-300"> Banks</span>
                    </p>
                </div>
            </div>

        </div>

        {{-- Recent Users Table --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md overflow-auto">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Latest Registered Users</h2>
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase">
                    <tr>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Status</th>
                        <th class="py-2 px-4">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\User::latest()->take(5)->get() as $user)
                        <tr
                            class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2 px-4">{{ $user->name }}</td>
                            <td class="py-2 px-4">{{ $user->email }}</td>
                            <td class="py-2 px-4">
                                <span
                                    class="inline-block px-2 py-1 text-xs rounded-full
                                    @if ($user->status === 'Active') bg-green-100 text-green-800
                                    @elseif($user->status === 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->status === 'Suspended') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="py-2 px-4">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Livewire Chart Component -->
        <div
            class="relative h-80 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            @livewire('dashboard.user-chart')
        </div>

        <!-- Progress Bar + Daily Logins -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Animated Progress Bar -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Verification Completion</h3>
                @php
                    $verified = \App\Models\User::whereNotNull('email_verified_at')->count();
                    $total = \App\Models\User::count();
                    $percent = $total > 0 ? round(($verified / $total) * 100) : 0;
                @endphp
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                    <div class="bg-green-500 h-4 transition-all duration-700 ease-out"
                        style="width: {{ $percent }}%">
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $percent }}% verified</p>
            </div>

            <!-- Daily Logins Placeholder -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Daily Logins</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Last login: {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Never' }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <livewire:dashboard.login-card />
            <livewire:dashboard.login-chart />
        </div>
    </div>
</x-layouts.app>
