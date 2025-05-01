<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BayiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IbuController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\PemeriksaanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard']);

Route::get('/ibu', [IbuController::class, 'ibu']);

Route::get('/kader', [KaderController::class, 'kader']);

Route::get('/bayi', [BayiController::class, 'bayi']);

Route::get('/jadwal', [JadwalController::class, 'jadwal']);

Route::get('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan']);
Route::get('/tambah_pemeriksaan', [PemeriksaanController::class, 'tambah_pemeriksaan']);
Route::get('/detail_pemeriksaan', [PemeriksaanController::class, 'detail_pemeriksaan']);
Route::get('/edit_pemeriksaan', [PemeriksaanController::class, 'edit_pemeriksaan']);

Route::get('/artikel', [ArtikelController::class, 'artikel']);