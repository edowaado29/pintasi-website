<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BayiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IbuController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\MotorikController;
use App\Http\Controllers\PemeriksaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard']);

Route::get('/ibu', [IbuController::class, 'ibu']);
Route::get('/detail_ibu/{id}', [IbuController::class, 'detail_ibu'])->name('detail_ibu');

Route::get('/bayi', [BayiController::class, 'bayi'])->name('bayi');
Route::get('/detail_bayi/{id}', [BayiController::class, 'detail_bayi'])->name('detail_bayi');

Route::get('/motorik', [MotorikController::class, 'motorik'])->name('motorik');
Route::get('/tambah_motorik', [MotorikController::class, 'tambah_motorik'])->name('tambah_motorik');
Route::post('/add_motorik', [MotorikController::class, 'add_motorik'])->name('add_motorik');
Route::get('/edit_motorik/{id}', [MotorikController::class, 'edit_motorik'])->name('edit_motorik');
Route::put('/update_motorik/{id}', [MotorikController::class, 'update_motorik'])->name('update_motorik');
Route::delete('/hapus_motorik/{id}', [MotorikController::class, 'hapus_motorik'])->name('hapus_motorik');

Route::get('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan'])->name('pemeriksaan');
Route::get('/tambah_pemeriksaan/{id_bayi}', [PemeriksaanController::class, 'tambah_pemeriksaan'])->name('tambah_pemeriksaan');
Route::post('/store_pemeriksaan', [PemeriksaanController::class, 'store_pemeriksaan'])->name('store_pemeriksaan');
Route::get('/detail_pemeriksaan/{id}', [PemeriksaanController::class, 'detail_pemeriksaan'])->name('detail_pemeriksaan');
Route::get('/edit_pemeriksaan/{id}', [PemeriksaanController::class, 'edit_pemeriksaan'])->name('edit_pemeriksaan');
Route::put('/update_pemeriksaan/{id}', [PemeriksaanController::class, 'update_pemeriksaan'])->name('update_pemeriksaan');
Route::delete('/delete_pemeriksaan/{id}', [PemeriksaanController::class, 'delete_pemeriksaan'])->name('delete_pemeriksaan');

Route::get('/jadwal', [JadwalController::class, 'jadwal']);
Route::get('/tambah_jadwal', [JadwalController::class, 'tambah_jadwal'])->name('tambah_jadwal');
Route::post('/add_jadwal', [JadwalController::class, 'add_jadwal'])->name('add_jadwal');
Route::get('/edit_jadwal/{id}', [JadwalController::class, 'edit_jadwal'])->name('edit_jadwal');
Route::put('/update_jadwal/{id}', [JadwalController::class, 'update_jadwal'])->name('update_jadwal');
Route::delete('/hapus_jadwal/{id}', [JadwalController::class, 'hapus_jadwal'])->name('hapus_jadwal');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/kader', [KaderController::class, 'kader'])->name('kader');
Route::get('/detail_kader/{id}', [KaderController::class, 'detail_kader'])->name('detail_kader');
Route::get('/tambah_kader', [KaderController::class, 'tambah_kader'])->name('tambah_kader');
Route::post('/add_kader', [KaderController::class, 'add_kader'])->name('add_kader');
Route::get('/edit_kader/{id}', [KaderController::class, 'edit_kader'])->name('edit_kader');
Route::put('/update_kader/{id}', [KaderController::class, 'update_kader'])->name('update_kader');
Route::delete('/hapus_kader/{id}', [KaderController::class, 'hapus_kader'])->name('hapus_kader');

Route::get('/artikel', [ArtikelController::class, 'artikel'])->name('artikel');
Route::get('/detail_artikel/{id}', [ArtikelController::class, 'detail_artikel'])->name('detail_artikel');
Route::get('/tambah_artikel', [ArtikelController::class, 'tambah_artikel'])->name('tambah_artikel');
Route::post('/add_artikel', [ArtikelController::class, 'add_artikel'])->name('add_artikel');
Route::get('/edit_artikel/{id}', [ArtikelController::class, 'edit_artikel'])->name('edit_artikel');
Route::put('/update_artikel/{id}', [ArtikelController::class, 'update_artikel'])->name('update_artikel');
Route::delete('/hapus_artikel/{id}', [ArtikelController::class, 'hapus_artikel'])->name('hapus_artikel');