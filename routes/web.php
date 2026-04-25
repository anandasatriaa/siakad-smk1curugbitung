<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AbsensiController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Provides login/logout routes
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Siswa
    Route::middleware(['role:siswa,superadmin'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::resource('absensi', AbsensiController::class);
    });
    
    // Superadmin & Admin
    Route::middleware(['role:superadmin,admin'])->name('admin.')->group(function () {
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MataPelajaranController::class);
        Route::resource('jadwal', JadwalPelajaranController::class);
    });

    // Nilai (Guru, Admin, Superadmin)
    Route::middleware(['role:guru,superadmin,admin'])->group(function () {
        Route::resource('nilai', NilaiController::class);
    });

    // Guru
    Route::middleware(['role:guru,superadmin'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('jadwal-mengajar', [JadwalPelajaranController::class, 'jadwalMengajar'])->name('jadwal.mengajar');
    });
});
