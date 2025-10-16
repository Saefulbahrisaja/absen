<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RekapAbsenController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanKPIController;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return redirect()->route('auth.login');
    
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login, Logout)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:3,1')
        ->name('login.submit');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| Protected Routes (Require Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Absensi Dashboard & Data API
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/absensi', [AbsenController::class, 'index'])->name('absensi.index');
    Route::get('/api/absensi-data', [DashboardController::class, 'getAbsensiData']);

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    Route::resource('/users', UserController::class);
    Route::post('/users/{user}/reset', [UserController::class, 'reset'])->name('users.reset');
    Route::post('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

    /*
    |--------------------------------------------------------------------------
    | Rekap Absensi
    |--------------------------------------------------------------------------
    */
    Route::get('/rekap', [RekapAbsenController::class, 'index'])->name('rekap.index');

    /*
    |--------------------------------------------------------------------------
    | Setting Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('setting')->group(function () {
        // Hari Libur
        Route::get('/hari-libur', [SettingController::class, 'hariLibur'])->name('setting.hari-libur');
        Route::post('/hari-libur', [SettingController::class, 'storeHariLibur'])->name('setting.hari-libur.store');
        Route::put('/hari-libur/{id}', [SettingController::class, 'updateHariLibur'])->name('setting.hari-libur.update');
        Route::delete('/hari-libur/{id}', [SettingController::class, 'deleteHariLibur'])->name('setting.hari-libur.delete');

        // Jadwal Kerja
        Route::get('/jadwal-kerja', [SettingController::class, 'jadwalKerja'])->name('setting.jadwal-kerja');
        Route::post('/jadwal-kerja', [SettingController::class, 'storeJadwalKerja'])->name('setting.jadwal-kerja.store');
        Route::put('/jadwal-kerja/{id}', [SettingController::class, 'updateJadwalKerja'])->name('setting.jadwal-kerja.update');
        Route::delete('/jadwal-kerja/{id}', [SettingController::class, 'deleteJadwalKerja'])->name('setting.jadwal-kerja.delete');
        Route::put('/jadwal-kerja/update-tanggal/{id}', [SettingController::class, 'updateTanggal']);

        // Task List
        Route::get('/task-list', [SettingController::class, 'taskList'])->name('setting.task-list');
        Route::post('/task-list', [SettingController::class, 'storeTaskList'])->name('setting.task-list.store');
        Route::put('/task-list/{id}', [SettingController::class, 'updateTaskList'])->name('setting.task-list.update');
        Route::delete('/task-list/{id}', [SettingController::class, 'deleteTaskList'])->name('setting.task-list.delete');

        // Jam Absen
        Route::get('/jam-absen', [SettingController::class, 'jamAbsen'])->name('setting.jam-absen');
        Route::post('/jam-absen', [SettingController::class, 'storeJadwalAbsen'])->name('setting.jam-absen.store');
        Route::put('/jam-absen/{id}', [SettingController::class, 'updateJadwalAbsen'])->name('setting.jam-absen.update');
        Route::delete('/jam-absen/{id}', [SettingController::class, 'deleteJadwalAbsen'])->name('setting.jam-absen.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->group(function () {
        Route::get('/kehadiran', [LaporanController::class, 'index'])->name('laporan.kehadiran');
        Route::get('/kpi', [LaporanKPIController::class, 'index'])->name('laporan.kpi');
        Route::get('/kehadiran/export', [LaporanController::class, 'exportLaporanKehadiran'])->name('laporan.kehadiran.export');
        Route::get('/kehadiran/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.kehadiran.pdf');
    });

    /*
    |--------------------------------------------------------------------------
    | PDF Export Manual
    |--------------------------------------------------------------------------
    */
    Route::get('/export-pdf', [AbsenController::class, 'exportPdf']);
});

Route::fallback(function () {
    return redirect()->route('login')->withErrors('Halaman tidak ditemukan atau akses tidak diizinkan.');
});
