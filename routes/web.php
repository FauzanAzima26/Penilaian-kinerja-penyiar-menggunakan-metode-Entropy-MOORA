<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenyiarController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/dashboard', Dashboard::class)->names('dashboard');

Route::get('/kriteria/data', [KriteriaController::class, 'getData'])->name('kriteria.data');
Route::resource('/kriteria', KriteriaController::class)->names('kriteria');

Route::post('/penilaian/update-nilai', [PenilaianController::class, 'updateNilai'])->name('penilaian.updateNilai');
Route::get('/penilaian/data', [PenilaianController::class, 'getData'])->name('penilaian.getData');
Route::resource('/penilaian', PenilaianController::class)->names('penilaian');

Route::post('/perhitungan/hitung', [PerhitunganController::class, 'hitung'])->name('perhitungan.hitung');
Route::get('/data-perankingan', [PerhitunganController::class, 'getData']);
Route::get('/hasil', function () {
    return view('hasil/index');
})->name('hasil');

Route::get('/penyiar/data', [PenyiarController::class, 'getData'])->name('penyiar.getData');
Route::resource('/penyiar', PenyiarController::class)->names('penyiar');

