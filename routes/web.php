<?php

use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/dashboard', Dashboard::class)->names('dashboard');

Route::get('/kriteria/data', [KriteriaController::class, 'getData'])->name('kriteria.data');
Route::resource('/kriteria', KriteriaController::class)->names('kriteria');
