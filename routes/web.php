<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import', [ImportController::class, 'index']);
Route::post('/import', [ImportController::class, 'import'])->name('import');