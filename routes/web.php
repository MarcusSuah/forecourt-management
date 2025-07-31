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
use App\Livewire\Admin\Tanks\TankManager;
use App\Livewire\Admin\Tanks\DippingManager;
use App\Livewire\Admin\Currencies\CurrencyManager;
use App\Livewire\Admin\ExchangeRates\ExchangeRateManager;
use App\Livewire\Admin\Banks\BankManager;
use App\Livewire\Admin\UnitPrices\PriceManager;
use App\Livewire\Admin\MeterCollections\MeterCollectionManager;

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
            Route::get('/tanks', TankManager::class)->name('tanks.index');
            Route::get('/tanks/dapping-manager', DippingManager::class)->name('dapping-manager.index');
            Route::get('/currencies', CurrencyManager::class)->name('currencies.index');
            Route::get('/exchange-rates', ExchangeRateManager::class)->name('exchange-rates.index');
            Route::get('/banks', BankManager::class)->name('banks.index');
            Route::get('/unit-prices', PriceManager::class)->name('unit-prices.index');
            Route::get('/meter-collections', MeterCollectionManager::class)->name('meter-collections.index');
        });
});

require __DIR__ . '/auth.php';
