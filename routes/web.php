<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NotificationController;

Route::get('/history', [HistoryController::class, 'index'])->name('history');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/devices', [DeviceController::class, 'index'])->name('devices');
Route::get('/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');

Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');

Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::post('/import', [ImportController::class, 'import'])->name('import.store');

Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications');