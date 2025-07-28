<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Admin\Products\ProductIndex;
use App\Livewire\Admin\Users\UserManager;
use App\Livewire\Admin\Dealers\DealerManager;
use App\Livewire\Admin\Stations\StationManager;
use App\Livewire\Admin\Shifts\ShiftManager;
use App\Livewire\Admin\Departments\DepartmentManager;
use App\Livewire\Admin\Designations\DesignationManager;
use App\Livewire\Admin\Employees\EmployeeManager;
use App\Livewire\Admin\Pumps\PumpManager;

//normal
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    //ADMIN ROUTE
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/products', ProductIndex::class)->name('products.index');
            Route::get('/users', UserManager::class)->name('users.index');
            Route::get('/dealers', DealerManager::class)->name('dealers.index');
            Route::get('/stations', StationManager::class)->name('stations.index');
            Route::get('/shifts', ShiftManager::class)->name('shifts.index');
            Route::get('/departments', DepartmentManager::class)->name('departments.index');
            Route::get('/designations', DesignationManager::class)->name('designations.index');
            Route::get('/employees', EmployeeManager::class)->name('employees.index');
            Route::get('/pumps', PumpManager::class)->name('pumps.index');
        });
});

require __DIR__ . '/auth.php';
