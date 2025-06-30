<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\RawDataController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/stations', [StationController::class, 'index'])->name('station.list');
// STATION
Route::prefix('station')->group(function () {
    Route::get('/list', [StationController::class, 'list'])->name('station.list');
});

// RAW DATA
//Route::get('/raw-data', [RawDataController::class, 'index'])->name('raw.index');
Route::get('/rawdata', [RawDataController::class, 'index'])->name('rawdata.index');
// LOCATION
//Route::get('/location', [LocationController::class, 'index'])->name('location.index');
Route::get('/location', [LocationController::class, 'index'])->name('location.index');

// REPORT
Route::get('/report/generate', [ReportController::class, 'generate'])->name('report.generate');

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    Route::get('/fetch-data', [DashboardController::class, 'fetchFromThingspeak']);

