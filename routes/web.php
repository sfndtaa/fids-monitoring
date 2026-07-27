<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;


Route::get('/', [DashboardController::class,'index']);

Route::get('/devices', [DeviceController::class,'index']);