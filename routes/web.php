<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/devices', [DeviceController::class, 'index'])->name('devices');

Route::get('/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');