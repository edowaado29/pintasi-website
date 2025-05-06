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

Route::get('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan'])->name('pemeriksaan');
Route::get('/tambah_pemeriksaan/{id_bayi}', [PemeriksaanController::class, 'tambah_pemeriksaan'])->name('tambah_pemeriksaan');
Route::post('/store_pemeriksaan', [PemeriksaanController::class, 'store_pemeriksaan'])->name('store_pemeriksaan');
Route::get('/detail_pemeriksaan/{id}', [PemeriksaanController::class, 'detail_pemeriksaan'])->name('detail_pemeriksaan');
Route::get('/edit_pemeriksaan/{id}', [PemeriksaanController::class, 'edit_pemeriksaan'])->name('edit_pemeriksaan');
Route::put('/update_pemeriksaan/{id}', [PemeriksaanController::class, 'update_pemeriksaan'])->name('update_pemeriksaan');
Route::delete('/delete_pemeriksaan/{id}', [PemeriksaanController::class, 'delete_pemeriksaan'])->name('delete_pemeriksaan');

Route::get('/jadwal', [JadwalController::class, 'jadwal']);

Route::get('/artikel', [ArtikelController::class, 'artikel']);