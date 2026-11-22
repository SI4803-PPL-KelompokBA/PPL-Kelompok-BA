<?php

use App\Http\Controllers\Esg\CalculationController;
use Illuminate\Support\Facades\Route;

Route::get('/calculations', [CalculationController::class, 'index'])
    ->name('calculations.index');

Route::post('/calculations/recalculate', [CalculationController::class, 'recalculate'])
    ->name('calculations.recalculate');
