<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WaktuAbsenController;
use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Login tanpa middleware (publik)
Route::post('/login', [AuthController::class, 'login']);
//Route::get('/kegiatan/jadwalkerja', [AbsenController::class, 'getJadwalKerja']);

//Route::get('/waktu-absen', [WaktuAbsenController::class, 'cek']);
// Optional: logout endpoint
Route::post('/logout', [AuthController::class, 'logout']);
// Group route yang membutuhkan token login (proteksi dengan Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/waktu-absen', [WaktuAbsenController::class, 'cek']);
    Route::post('/kegiatan/update-status', [AbsenController::class, 'updateStatusKegiatan']);
    Route::get('/kegiatan/list/{user_id}', [AbsenController::class, 'getAllKegiatan']);
    Route::get('/kegiatan/jadwalkerja', [AbsenController::class, 'getJadwalKerja']);

    Route::post('/absen', [AbsenController::class, 'store']);
    Route::get('/absen/today/{user_id}', [AbsenController::class, 'getToday']);
    Route::post('/kegiatan', [AbsenController::class, 'storeKegiatan']);
    Route::get('/absen/list/{user_id}', [AbsenController::class, 'getAll']);
    Route::get('/absen/{user_id}/bulan', [AbsenController::class, 'getByMonth']);
    Route::get('/kegiatan/list', [AbsenController::class, 'listKegiatanByUser']);
    
//Route::post('/admin/set-jadwal', [AdminController::class, 'setJadwal']);
    
});
