<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inputter\EmissionController;
use App\Http\Controllers\Inputter\LandCarbonDataController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------- DATA INPUTTER ----------

    Route::middleware('role:data_inputter')->prefix('inputter')->name('inputter.')->group(function () {
    Route::get('emissions', [EmissionController::class, 'index'])->name('emissions.index');
    Route::get('emissions/{kategori}/create', [EmissionController::class, 'create'])->name('emissions.create');
    Route::post('emissions/{kategori}', [EmissionController::class, 'store'])->name('emissions.store');
    Route::delete('emissions/{emission}', [EmissionController::class, 'destroy'])->name('emissions.destroy');

    Route::get('land-carbon-data', [LandCarbonDataController::class, 'index'])
        ->name('land-carbon-data.index');

    Route::get('land-carbon-data/create', [LandCarbonDataController::class, 'create'])
        ->name('land-carbon-data.create');

    Route::post('land-carbon-data', [LandCarbonDataController::class, 'store'])
        ->name('land-carbon-data.store');

    Route::get('land-carbon-data/{landCarbonData}', [LandCarbonDataController::class, 'show'])
        ->name('land-carbon-data.show');

    Route::get('land-carbon-data/{landCarbonData}/edit', [LandCarbonDataController::class, 'edit'])
        ->name('land-carbon-data.edit');

    Route::put('land-carbon-data/{landCarbonData}', [LandCarbonDataController::class, 'update'])
        ->name('land-carbon-data.update');

    Route::delete('land-carbon-data/{landCarbonData}', [LandCarbonDataController::class, 'destroy'])
        ->name('land-carbon-data.destroy');
    });


    // ---------- ESG OFFICER: tiap fitur punya file sendiri di routes/esg/ ----------
    Route::middleware('role:esg_officer')->prefix('esg')->name('esg.')->group(function () {
        foreach (glob(__DIR__ . '/esg/*.php') as $file) {
            require $file;
        }
    });

    // ---------- AUDITOR (Sprint 2) ----------
    Route::middleware('role:auditor')->prefix('auditor')->name('auditor.')->group(function () {
        foreach (glob(__DIR__ . '/auditor/*.php') as $file) {
            require $file;
        }
    });
});

require __DIR__ . '/auth.php';