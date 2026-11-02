<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransportController;

Route::get('/', function () {
    return redirect()->route('transports.index');
});

Route::get('/transports', [TransportController::class, 'index'])->name('transports.index');
Route::post('/transports', [TransportController::class, 'store'])->name('transports.store');
Route::delete('/transports/{id}', [TransportController::class, 'destroy'])->name('transports.destroy');