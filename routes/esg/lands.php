<?php

use App\Http\Controllers\Esg\LandController;
use Illuminate\Support\Facades\Route;

Route::resource('lands', LandController::class)->except('show');
