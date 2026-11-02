<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectricityController;
use App\Http\Controllers\LogisticsController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk Pencatatan Konsumsi Listrik
Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
Route::post('/electricity', [ElectricityController::class, 'store'])->name('electricity.store');

// Route untuk Logistik Ekspor Pertanian
Route::get('/logistics', [LogisticsController::class, 'index'])->name('logistics.index');
Route::post('/logistics', [LogisticsController::class, 'store'])->name('logistics.store');