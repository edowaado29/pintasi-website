<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BayiController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MotorikController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PerkembanganMotorikController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
// Profile routes
    Route::prefix('profile')->group(function () {
        Route::post('/editprofile', [ProfileController::class, 'update']);
        Route::get('/showakun', [ProfileController::class, 'show']);
    });


// Bayi routes
    Route::prefix('bayis')->group(function () {
        Route::post('/addbayis', [BayiController::class, 'store']);
        Route::get('/bayis/trashed', [BayiController::class, 'trashed']);
        Route::post('/{id}/restore', [BayiController::class, 'restore']);
        Route::get('/showbayis', [BayiController::class, 'showBayis']);
    });

// File upload route
    Route::get('uploads/foto/{filename}', function ($filename) {
        $path = public_path('uploads/foto/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    });
});


// Pemeriksaan routes
Route::prefix('pemeriksaan')->group(function () {
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'get_pemeriksaan']);
});


// Motorik Routes
Route::get('/motorik_all', [MotorikController::class, 'getMotorik']);
Route::prefix('motorik')->group(function () {
    Route::get('/motorik', [MotorikController::class, 'getMotorik']);
});


// Perkembangan Motorik routes
Route::post('/ceklis', [PerkembanganMotorikController::class, 'ceklis']);
Route::post('/uncheck', [PerkembanganMotorikController::class, 'uncheck']);
Route::get('/milestone_usia/{id}', [PerkembanganMotorikController::class, 'milestoneByAge']);
Route::get('/motorik/milestone-semua/{bayi_id}', [PerkembanganMotorikController::class, 'semuaMilestoneBayi']);


//Artikel Routes
Route::get('/api/artikel', [ArtikelController::class, 'showAll']);


//Notifications Routes
Route::post('/send-notification', [NotificationController::class, 'sendTestNotification']);
if (app()->environment('local')) {
    Route::get('/test-firebase', function (App\Services\FirebaseService $firebase) {
        $token = env('TEST_FCM_TOKEN');
        $firebase->sendNotification($token, 'Test Title', 'Test Body');
        return 'Notifikasi dikirim';
    });
}
