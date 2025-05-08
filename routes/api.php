<?php

use App\Http\Controllers\BayiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/editprofile', [ProfileController::class, 'update']);
    Route::get('/showakun', [ProfileController::class, 'show']);
    Route::post('/addbayis', [BayiController::class, 'store']);
    // Route::resource('/bayis', BayiController::class);
    Route::get('bayis/trashed', [BayiController::class, 'trashed']);
    Route::post('bayis/{id}/restore', [BayiController::class, 'restore']);
    Route::get('/showbayis', [BayiController::class, 'index']);
    Route::get('uploads/foto/{filename}', function ($filename) {
        $path = public_path('uploads/foto/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            abort(404);
        }
    });
});

Route::get('/bayiss', [BayiController::class, 'apiIndex']);
Route::post('/bayis', [BayiController::class, 'apiStore']);

Route::get('/pemeriksaanss', [PemeriksaanController::class, 'apiIndex']);
Route::post('/Storepemeriksaans', [PemeriksaanController::class, 'apiStore']);
Route::get('/search-bayi', [PemeriksaanController::class, 'searchBayi']);