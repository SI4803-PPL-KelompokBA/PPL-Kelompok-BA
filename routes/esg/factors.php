<?php

use App\Http\Controllers\Esg\EmissionFactorController;
use Illuminate\Support\Facades\Route;

Route::resource('emission-factors', EmissionFactorController::class)
    ->except('show')
    ->parameters(['emission-factors' => 'factor']);