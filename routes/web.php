<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BayiController;
use App\Http\Controllers\DaftarBahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IbuController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\MotorikController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Container\Attributes\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/loginUser', [AuthController::class, 'loginUser'])->name('loginUser');
});

Route::middleware(['auth', 'role:kader'])->group(function () {
    Route::get('/dashboard/kader', [DashboardController::class, 'dashboard_kader'])->name('dashboard_kader');

    // route profile
    Route::get('/profil', [ProfileController::class, 'profil'])->name('profil');
    Route::put('/updateProfile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::put('/uploadImg', [ProfileController::class, 'uploadImg'])->name('uploadImg');
    Route::put('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');

    // route bayi kader
    Route::get('/bayi_kader', [BayiController::class, 'bayi_kader'])->name('bayi_kader');
    Route::get('/detail_bayi_kader/{id}', [BayiController::class, 'detail_bayi_kader'])->name('detail_bayi_kader');
    Route::get('/tambah_bayi_kader', [BayiController::class, 'tambah_bayi_kader'])->name('tambah_bayi_kader');
    Route::post('/add_bayi_kader', [BayiController::class, 'add_bayi_kader'])->name('add_bayi_kader');
    Route::get('/edit_bayi_kader/{id}', [BayiController::class, 'edit_bayi_kader'])->name('edit_bayi_kader');
    Route::put('/update_bayi_kader/{id}', [BayiController::class, 'update_bayi_kader'])->name('update_bayi_kader');
    Route::delete('/hapus_bayi_kader/{id}', [BayiController::class, 'hapus_bayi_kader'])->name('hapus_bayi_kader');

    //route pemeriksaan kader
    Route::get('/pemeriksaan_kader', [PemeriksaanController::class, 'pemeriksaan_kader'])->name('pemeriksaan_kader');
    Route::get('/tambah_pemeriksaan_kader/{id_bayi}', [PemeriksaanController::class, 'tambah_pemeriksaan_kader'])->name('tambah_pemeriksaan_kader');
    Route::post('/store_pemeriksaan_kader', [PemeriksaanController::class, 'store_pemeriksaan_kader'])->name('store_pemeriksaan_kader');
    Route::get('/detail_pemeriksaan_kader/{id}', [PemeriksaanController::class, 'detail_pemeriksaan_kader'])->name('detail_pemeriksaan_kader');
    Route::get('/edit_pemeriksaan_kader/{id}', [PemeriksaanController::class, 'edit_pemeriksaan_kader'])->name('edit_pemeriksaan_kader');
    Route::put('/update_pemeriksaan_kader/{id}', [PemeriksaanController::class, 'update_pemeriksaan_kader'])->name('update_pemeriksaan_kader');
    Route::delete('/delete_pemeriksaan_kader/{id}', [PemeriksaanController::class, 'delete_pemeriksaan_kader'])->name('delete_pemeriksaan_kader');

});

Route::middleware(['auth', 'role:bidan'])->group(function () {
    Route::get('/dashboard/bidan', [DashboardController::class, 'dashboard'])->name('dashboard');

    // route profile
    Route::get('/profil', [ProfileController::class, 'profil'])->name('profil');
    Route::put('/updateProfile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::put('/uploadImg', [ProfileController::class, 'uploadImg'])->name('uploadImg');
    Route::put('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');

    //route ibu
    Route::get('/ibu', [IbuController::class, 'ibu'])->name('ibu');
    Route::get('/detail_ibu/{id}', [IbuController::class, 'detail_ibu'])->name('detail_ibu');
    Route::get('/tambah_ibu', [IbuController::class, 'tambah_ibu'])->name('tambah_ibu');
    Route::post('/add_ibu', [IbuController::class, 'add_ibu'])->name('add_ibu');
    Route::get('/edit_ibu/{id}', [IbuController::class, 'edit_ibu'])->name('edit_ibu');
    Route::put('/update_ibu/{id}', [IbuController::class, 'update_ibu'])->name('update_ibu');
    Route::delete('/hapus_ibu/{id}', [IbuController::class, 'hapus_ibu'])->name('hapus_ibu');

    //route bayi
    Route::get('/bayi', [BayiController::class, 'bayi'])->name('bayi');
    Route::get('/detail_bayi/{id}', [BayiController::class, 'detail_bayi'])->name('detail_bayi');
    Route::get('/tambah_bayi', [BayiController::class, 'tambah_bayi'])->name('tambah_bayi');
    Route::post('/add_bayi', [BayiController::class, 'add_bayi'])->name('add_bayi');
    Route::get('/edit_bayi/{id}', [BayiController::class, 'edit_bayi'])->name('edit_bayi');
    Route::put('/update_bayi/{id}', [BayiController::class, 'update_bayi'])->name('update_bayi');
    Route::delete('/hapus_bayi/{id}', [BayiController::class, 'hapus_bayi'])->name('hapus_bayi');

    //route resep
    Route::get('/resep', [ResepController::class, 'resep'])->name('resep');
    Route::get('/detail_resep/{id}', [ResepController::class, 'detail_resep'])->name('detail_resep');
    Route::get('/tambah_resep', [ResepController::class, 'tambah_resep'])->name('tambah_resep');
    Route::post('/add_resep', [ResepController::class, 'add_resep'])->name('add_resep');
    Route::get('/edit_resep/{id}', [ResepController::class, 'edit_resep'])->name('edit_resep');
    Route::put('/update_resep/{id}', [ResepController::class, 'update_resep'])->name('update_resep');
    Route::delete('/hapus_resep/{id}', [ResepController::class, 'hapus_resep'])->name('hapus_resep');

    //route daftar bahan
    Route::get('/daftar_bahan', [DaftarBahanController::class, 'daftar_bahan'])->name('daftar_bahan');
    Route::get('/detail_bahan/{id}', [DaftarBahanController::class, 'detail_bahan'])->name('detail_bahan');
    Route::get('/tambah_bahan', [DaftarBahanController::class, 'tambah_bahan'])->name('tambah_bahan');
    Route::post('/add_bahan', [DaftarBahanController::class, 'add_bahan'])->name('add_bahan');
    Route::get('/edit_bahan/{id}', [DaftarBahanController::class, 'edit_bahan'])->name('edit_bahan');
    Route::put('/update_bahan/{id}', [DaftarBahanController::class, 'update_bahan'])->name('update_bahan');
    Route::delete('/hapus_bahan/{id}', [DaftarBahanController::class, 'hapus_bahan'])->name('hapus_bahan');
    Route::post('/import_bahan', [DaftarBahanController::class, 'import_bahan'])->name('import_bahan');

    //route motorik
    Route::get('/motorik', [MotorikController::class, 'motorik'])->name('motorik');
    Route::get('/tambah_motorik', [MotorikController::class, 'tambah_motorik'])->name('tambah_motorik');
    Route::post('/add_motorik', [MotorikController::class, 'add_motorik'])->name('add_motorik');
    Route::get('/edit_motorik/{id}', [MotorikController::class, 'edit_motorik'])->name('edit_motorik');
    Route::put('/update_motorik/{id}', [MotorikController::class, 'update_motorik'])->name('update_motorik');
    Route::delete('/hapus_motorik/{id}', [MotorikController::class, 'hapus_motorik'])->name('hapus_motorik');

    //route pemeriksaan
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan'])->name('pemeriksaan');
    Route::get('/tambah_pemeriksaan/{id_bayi}', [PemeriksaanController::class, 'tambah_pemeriksaan'])->name('tambah_pemeriksaan');
    Route::post('/store_pemeriksaan', [PemeriksaanController::class, 'store_pemeriksaan'])->name('store_pemeriksaan');
    Route::get('/detail_pemeriksaan/{id}', [PemeriksaanController::class, 'detail_pemeriksaan'])->name('detail_pemeriksaan');
    Route::get('/edit_pemeriksaan/{id}', [PemeriksaanController::class, 'edit_pemeriksaan'])->name('edit_pemeriksaan');
    Route::put('/update_pemeriksaan/{id}', [PemeriksaanController::class, 'update_pemeriksaan'])->name('update_pemeriksaan');
    Route::delete('/delete_pemeriksaan/{id}', [PemeriksaanController::class, 'delete_pemeriksaan'])->name('delete_pemeriksaan');

    //route jadwal
    Route::get('/jadwal', [JadwalController::class, 'jadwal'])->name('jadwal');
    Route::get('/tambah_jadwal', [JadwalController::class, 'tambah_jadwal'])->name('tambah_jadwal');
    Route::post('/add_jadwal', [JadwalController::class, 'add_jadwal'])->name('add_jadwal');
    Route::get('/edit_jadwal/{id}', [JadwalController::class, 'edit_jadwal'])->name('edit_jadwal');
    Route::put('/update_jadwal/{id}', [JadwalController::class, 'update_jadwal'])->name('update_jadwal');
    Route::delete('/hapus_jadwal/{id}', [JadwalController::class, 'hapus_jadwal'])->name('hapus_jadwal');

    //route kader
    Route::get('/kader', [KaderController::class, 'kader'])->name('kader');
    Route::get('/detail_kader/{id}', [KaderController::class, 'detail_kader'])->name('detail_kader');
    Route::get('/tambah_kader', [KaderController::class, 'tambah_kader'])->name('tambah_kader');
    Route::post('/add_kader', [KaderController::class, 'add_kader'])->name('add_kader');
    Route::get('/edit_kader/{id}', [KaderController::class, 'edit_kader'])->name('edit_kader');
    Route::put('/update_kader/{id}', [KaderController::class, 'update_kader'])->name('update_kader');
    Route::delete('/hapus_kader/{id}', [KaderController::class, 'hapus_kader'])->name('hapus_kader');

    //route artikel
    Route::get('/artikel', [ArtikelController::class, 'artikel'])->name('artikel');
    Route::get('/detail_artikel/{id}', [ArtikelController::class, 'detail_artikel'])->name('detail_artikel');
    Route::get('/tambah_artikel', [ArtikelController::class, 'tambah_artikel'])->name('tambah_artikel');
    Route::post('/add_artikel', [ArtikelController::class, 'add_artikel'])->name('add_artikel');
    Route::get('/edit_artikel/{id}', [ArtikelController::class, 'edit_artikel'])->name('edit_artikel');
    Route::put('/update_artikel/{id}', [ArtikelController::class, 'update_artikel'])->name('update_artikel');
    Route::delete('/hapus_artikel/{id}', [ArtikelController::class, 'hapus_artikel'])->name('hapus_artikel');
});


// Route untuk logout
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});