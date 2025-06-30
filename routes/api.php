<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware('api')->group(function () {
    Route::get('/fetch-thingspeak', [DashboardController::class, 'fetchFromThingspeak']);
});