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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/loginUser', [AuthController::class, 'loginUser'])->name('loginUser');
    Route::post('/forgot_password_act', [AuthController::class, 'forgot_password_act'])->name('forgot_password_act');
    Route::get('/validasi_forgot_password/{token}', [AuthController::class, 'validasi_forgot_password'])->name('validasi_forgot_password');
    Route::post('/validasi_forgot_password_act', [AuthController::class, 'validasi_forgot_password_act'])->name('validasi_forgot_password_act');
});

Route::middleware(['auth', 'role:kader'])->group(function () {
    Route::get('k/dashboard', [DashboardController::class, 'k_dashboard'])->name('k/dashboard');

    // route profile
    Route::get('k/profil', [ProfileController::class, 'k_profil'])->name('k/profil');
    Route::put('k/updateProfile', [ProfileController::class, 'k_updateProfile'])->name('k/updateProfile');
    Route::put('k/uploadImg', [ProfileController::class, 'k_uploadImg'])->name('k/uploadImg');
    Route::put('k/updatePassword', [ProfileController::class, 'k_updatePassword'])->name('k/updatePassword');

    //route ibu
    Route::get('k/ibu', [IbuController::class, 'k_ibu'])->name('k/ibu');
    Route::get('k/detail_ibu/{id}', [IbuController::class, 'k_detail_ibu'])->name('k/detail_ibu');
    Route::get('k/tambah_ibu', [IbuController::class, 'k_tambah_ibu'])->name('k/tambah_ibu');
    Route::post('k/add_ibu', [IbuController::class, 'k_add_ibu'])->name('k/add_ibu');
    Route::get('k/edit_ibu/{id}', [IbuController::class, 'k_edit_ibu'])->name('k/edit_ibu');
    Route::put('k/update_ibu/{id}', [IbuController::class, 'k_update_ibu'])->name('k/update_ibu');
    Route::delete('k/hapus_ibu/{id}', [IbuController::class, 'k_hapus_ibu'])->name('k/hapus_ibu');

    // route bayi kader
    Route::get('k/bayi', [BayiController::class, 'k_bayi'])->name('k/bayi');
    Route::get('k/detail_bayi/{id}', [BayiController::class, 'k_detail_bayi'])->name('k/detail_bayi');
    Route::get('k/tambah_bayi', [BayiController::class, 'k_tambah_bayi'])->name('k/tambah_bayi');
    Route::post('k/add_bayi', [BayiController::class, 'k_add_bayi'])->name('k/add_bayi');
    Route::get('k/edit_bayi/{id}', [BayiController::class, 'k_edit_bayi'])->name('k/edit_bayi');
    Route::put('k/update_bayi/{id}', [BayiController::class, 'k_update_bayi'])->name('k/update_bayi');
    Route::delete('k/hapus_bayi/{id}', [BayiController::class, 'k_hapus_bayi'])->name('k/hapus_bayi');

    // route pemeriksaan kader
    Route::get('k/pemeriksaan', [PemeriksaanController::class, 'k_pemeriksaan'])->name('k/pemeriksaan');
    Route::get('k/tambah_pemeriksaan/{id_bayi}', [PemeriksaanController::class, 'k_tambah_pemeriksaan'])->name('k/tambah_pemeriksaan');
    Route::post('k/store_pemeriksaan', [PemeriksaanController::class, 'k_store_pemeriksaan'])->name('k/store_pemeriksaan');
    Route::get('k/detail_pemeriksaan/{id}', [PemeriksaanController::class, 'k_detail_pemeriksaan'])->name('k/detail_pemeriksaan');
    Route::get('k/edit_pemeriksaan/{id}', [PemeriksaanController::class, 'k_edit_pemeriksaan'])->name('k/edit_pemeriksaan');
    Route::put('k/update_pemeriksaan/{id}', [PemeriksaanController::class, 'k_update_pemeriksaan'])->name('k/update_pemeriksaan');
    Route::delete('k/delete_pemeriksaan/{id}', [PemeriksaanController::class, 'k_delete_pemeriksaan'])->name('k/delete_pemeriksaan');

});

Route::middleware(['auth', 'role:bidan'])->group(function () {
    Route::get('b/dashboard', [DashboardController::class, 'b_dashboard'])->name('b/dashboard');

    // ProfileController
    Route::get('b/profil', [ProfileController::class, 'b_profil'])->name('b/profil');
    Route::put('b/updateProfile', [ProfileController::class, 'b_updateProfile'])->name('b/updateProfile');
    Route::put('b/uploadImg', [ProfileController::class, 'b_uploadImg'])->name('b/uploadImg');
    Route::put('b/updatePassword', [ProfileController::class, 'b_updatePassword'])->name('b/updatePassword');

    // IbuController
    Route::get('b/ibu', [IbuController::class, 'b_ibu'])->name('b/ibu');
    Route::get('b/detail_ibu/{id}', [IbuController::class, 'b_detail_ibu'])->name('b/detail_ibu');
    Route::get('b/tambah_ibu', [IbuController::class, 'b_tambah_ibu'])->name('b/tambah_ibu');
    Route::post('b/add_ibu', [IbuController::class, 'b_add_ibu'])->name('b/add_ibu');
    Route::get('b/edit_ibu/{id}', [IbuController::class, 'b_edit_ibu'])->name('b/edit_ibu');
    Route::put('b/update_ibu/{id}', [IbuController::class, 'b_update_ibu'])->name('b/update_ibu');
    Route::delete('b/hapus_ibu/{id}', [IbuController::class, 'b_hapus_ibu'])->name('b/hapus_ibu');

    // BayiController
    Route::get('b/bayi', [BayiController::class, 'b_bayi'])->name('b/bayi');
    Route::get('b/detail_bayi/{id}', [BayiController::class, 'b_detail_bayi'])->name('b/detail_bayi');
    Route::get('b/tambah_bayi', [BayiController::class, 'b_tambah_bayi'])->name('b/tambah_bayi');
    Route::post('b/add_bayi', [BayiController::class, 'b_add_bayi'])->name('b/add_bayi');
    Route::get('b/edit_bayi/{id}', [BayiController::class, 'b_edit_bayi'])->name('b/edit_bayi');
    Route::put('b/update_bayi/{id}', [BayiController::class, 'b_update_bayi'])->name('b/update_bayi');
    Route::delete('b/hapus_bayi/{id}', [BayiController::class, 'b_hapus_bayi'])->name('b/hapus_bayi');

    // ResepController
    Route::get('b/resep', [ResepController::class, 'b_resep'])->name('b/resep');
    Route::get('b/detail_resep/{id}', [ResepController::class, 'b_detail_resep'])->name('b/detail_resep');
    Route::get('b/tambah_resep', [ResepController::class, 'b_tambah_resep'])->name('b/tambah_resep');
    Route::post('b/add_resep', [ResepController::class, 'b_add_resep'])->name('b/add_resep');
    Route::get('b/edit_resep/{id}', [ResepController::class, 'b_edit_resep'])->name('b/edit_resep');
    Route::put('b/update_resep/{id}', [ResepController::class, 'b_update_resep'])->name('b/update_resep');
    Route::delete('b/hapus_resep/{id}', [ResepController::class, 'b_hapus_resep'])->name('b/hapus_resep');

    // DaftarBahanController
    Route::get('b/daftar_bahan', [DaftarBahanController::class, 'b_daftar_bahan'])->name('b/daftar_bahan');
    Route::get('b/detail_bahan/{id}', [DaftarBahanController::class, 'b_detail_bahan'])->name('b/detail_bahan');
    Route::get('b/tambah_bahan', [DaftarBahanController::class, 'b_tambah_bahan'])->name('b/tambah_bahan');
    Route::post('b/add_bahan', [DaftarBahanController::class, 'b_add_bahan'])->name('b/add_bahan');
    Route::get('b/edit_bahan/{id}', [DaftarBahanController::class, 'b_edit_bahan'])->name('b/edit_bahan');
    Route::put('b/update_bahan/{id}', [DaftarBahanController::class, 'b_update_bahan'])->name('b/update_bahan');
    Route::delete('b/hapus_bahan/{id}', [DaftarBahanController::class, 'b_hapus_bahan'])->name('b/hapus_bahan');
    Route::post('b/import_bahan', [DaftarBahanController::class, 'b_import_bahan'])->name('b/import_bahan');

    // MotorikController
    Route::get('b/motorik', [MotorikController::class, 'b_motorik'])->name('b/motorik');
    Route::get('b/tambah_motorik', [MotorikController::class, 'b_tambah_motorik'])->name('b/tambah_motorik');
    Route::post('b/add_motorik', [MotorikController::class, 'b_add_motorik'])->name('b/add_motorik');
    Route::get('b/edit_motorik/{id}', [MotorikController::class, 'b_edit_motorik'])->name('b/edit_motorik');
    Route::put('b/update_motorik/{id}', [MotorikController::class, 'b_update_motorik'])->name('b/update_motorik');
    Route::delete('b/hapus_motorik/{id}', [MotorikController::class, 'b_hapus_motorik'])->name('b/hapus_motorik');

    // PemeriksaanController
    Route::get('b/pemeriksaan', [PemeriksaanController::class, 'b_pemeriksaan'])->name('b/pemeriksaan');
    Route::get('b/tambah_pemeriksaan/{id_bayi}', [PemeriksaanController::class, 'b_tambah_pemeriksaan'])->name('b/tambah_pemeriksaan');
    Route::post('b/store_pemeriksaan', [PemeriksaanController::class, 'b_store_pemeriksaan'])->name('b/store_pemeriksaan');
    Route::get('b/detail_pemeriksaan/{id}', [PemeriksaanController::class, 'b_detail_pemeriksaan'])->name('b/detail_pemeriksaan');
    Route::get('b/edit_pemeriksaan/{id}', [PemeriksaanController::class, 'b_edit_pemeriksaan'])->name('b/edit_pemeriksaan');
    Route::put('b/update_pemeriksaan/{id}', [PemeriksaanController::class, 'b_update_pemeriksaan'])->name('b/update_pemeriksaan');
    Route::delete('b/delete_pemeriksaan/{id}', [PemeriksaanController::class, 'b_delete_pemeriksaan'])->name('b/delete_pemeriksaan');

    // JadwalController
    Route::get('b/jadwal', [JadwalController::class, 'b_jadwal'])->name('b/jadwal');
    Route::get('b/tambah_jadwal', [JadwalController::class, 'b_tambah_jadwal'])->name('b/tambah_jadwal');
    Route::post('b/add_jadwal', [JadwalController::class, 'b_add_jadwal'])->name('b/add_jadwal');
    Route::get('b/edit_jadwal/{id}', [JadwalController::class, 'b_edit_jadwal'])->name('b/edit_jadwal');
    Route::put('b/update_jadwal/{id}', [JadwalController::class, 'b_update_jadwal'])->name('b/update_jadwal');
    Route::delete('b/hapus_jadwal/{id}', [JadwalController::class, 'b_hapus_jadwal'])->name('b/hapus_jadwal');

    // KaderController
    Route::get('b/kader', [KaderController::class, 'b_kader'])->name('b/kader');
    Route::get('b/detail_kader/{id}', [KaderController::class, 'b_detail_kader'])->name('b/detail_kader');
    Route::get('b/tambah_kader', [KaderController::class, 'b_tambah_kader'])->name('b/tambah_kader');
    Route::post('b/add_kader', [KaderController::class, 'b_add_kader'])->name('b/add_kader');
    Route::get('b/edit_kader/{id}', [KaderController::class, 'b_edit_kader'])->name('b/edit_kader');
    Route::put('b/update_kader/{id}', [KaderController::class, 'b_update_kader'])->name('b/update_kader');
    Route::delete('b/hapus_kader/{id}', [KaderController::class, 'b_hapus_kader'])->name('b/hapus_kader');

    // ArtikelController
    Route::get('b/artikel', [ArtikelController::class, 'b_artikel'])->name('b/artikel');
    Route::get('b/artikel/{id}', [ArtikelController::class, 'b_detail_artikel'])->name('b/detail-artikel');
    Route::get('b/detail_artikel/{id}', [ArtikelController::class, 'b_detail_artikel'])->name('b/detail_artikel');
    Route::get('b/tambah_artikel', [ArtikelController::class, 'b_tambah_artikel'])->name('b/tambah_artikel');
    Route::post('b/add_artikel', [ArtikelController::class, 'b_add_artikel'])->name('b/add_artikel');
    Route::get('b/edit_artikel/{id}', [ArtikelController::class, 'b_edit_artikel'])->name('b/edit_artikel');
    Route::put('b/update_artikel/{id}', [ArtikelController::class, 'b_update_artikel'])->name('b/update_artikel');
    Route::delete('b/hapus_artikel/{id}', [ArtikelController::class, 'b_hapus_artikel'])->name('b/hapus_artikel');

});


// Route untuk logout
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});