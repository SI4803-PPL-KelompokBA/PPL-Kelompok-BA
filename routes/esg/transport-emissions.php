<?php

use App\Http\Controllers\Esg\TransportEmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/transport-emissions', [TransportEmissionController::class, 'index'])
    ->name('transport-emissions.index');