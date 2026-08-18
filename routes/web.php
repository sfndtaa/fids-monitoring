<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // Devices
    Route::get('/devices', [DeviceController::class, 'index'])
        ->name('devices');

    Route::get('/devices/{device}', [DeviceController::class, 'show'])
        ->name('devices.show');


    // Monitoring
    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring');

    Route::post('/monitoring/ping/{device}', [MonitoringController::class, 'ping'])
        ->name('monitoring.ping');

    Route::post('/monitoring/ping-batch', [MonitoringController::class, 'pingBatch'])
        ->name('monitoring.ping-batch');

    Route::get('/monitoring/data', [MonitoringController::class, 'data'])
        ->name('monitoring.data');


    // History
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history');


    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications');


    // Import
    Route::get('/import', [ImportController::class, 'index'])
        ->name('import.index');

    Route::post('/import', [ImportController::class, 'import'])
        ->name('import.store');


    /*
    |--------------------------------------------------------------------------
    | User Management (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

});

require __DIR__.'/auth.php';