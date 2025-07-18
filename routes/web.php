<?php

use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::resource('/dashboard', DashboardController::class)->names('dashboard');
Route::resource('/penilaian', PenilaianController::class)->names('penilaian');
