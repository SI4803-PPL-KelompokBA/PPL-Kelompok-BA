<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::view('/', 'welcome')->name('welcome');

//--------------------------
// Login Page
//---------------------------
Route::get('/login', [LoginController::class, 'show'])
    ->name('login');

// Proses Login
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('cbam.login.process');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('cbam.logout');


//---------------------------
// Dashboard berdasarkan role
//---------------------------
Route::middleware('auth')->group(function () {

        
    Route::middleware(['auth', 'role:inputter'])->group(function () {
        Route::view('/inputter/dashboard', 'dashboards.inputter')
            ->name('inputter.dashboard');

    });

   Route::middleware(['auth', 'role:esg_officer'])->group(function () {
        Route::view('/esg/dashboard', 'dashboards.esg')
          ->name('esg.dashboard');

    });

    Route::middleware(['auth', 'role:auditor'])->group(function () {
        Route::view('/auditor/dashboard', 'dashboards.auditor')
            ->name('auditor.dashboard');

    });

});