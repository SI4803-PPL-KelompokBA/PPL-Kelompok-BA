<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TargetController;

Route::get('/targets', [TargetController::class, 'index'])
    ->name('targets.index');

Route::patch('/targets/{land}', [TargetController::class, 'update'])
    ->name('targets.update');

Route::delete('/targets/{land}', [TargetController::class, 'destroy'])
    ->name('targets.destroy');
