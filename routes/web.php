<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LegalisirController;
use App\Http\Controllers\PengambilanIjazahController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/bantuan', 'help')->name('help');

    // Profil & password
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'password'])->name('profile.password');

    // ===== ADMIN saja: master data =====
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::resource('jenis-dokumen', JenisDokumenController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['jenis-dokumen' => 'jenis']);
    });

    // ===== ADMIN & PETUGAS =====
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('kotak-masuk', [InboxController::class, 'index'])->name('inbox.index');

        Route::resource('documents', DocumentController::class)->except(['index', 'show']);

        Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'status'])->name('peminjaman.status');
        Route::patch('peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');

        Route::patch('legalisir/{legalisir}/status', [LegalisirController::class, 'status'])->name('legalisir.status');
        Route::patch('legalisir/{legalisir}/selesai', [LegalisirController::class, 'selesai'])->name('legalisir.selesai');

        Route::patch('ijazah/{ijazah}/status', [PengambilanIjazahController::class, 'status'])->name('ijazah.status');
        Route::patch('ijazah/{ijazah}/serahkan', [PengambilanIjazahController::class, 'serahkan'])->name('ijazah.serahkan');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
        Route::get('laporan/csv', [LaporanController::class, 'csv'])->name('laporan.csv');
    });

    // ===== SISWA saja: mengajukan layanan =====
    Route::middleware('role:siswa')->group(function () {
        Route::resource('peminjaman', PeminjamanController::class)->only(['create', 'store', 'destroy']);
        Route::resource('legalisir', LegalisirController::class)->only(['create', 'store', 'destroy']);
        Route::resource('ijazah', PengambilanIjazahController::class)->only(['create', 'store', 'destroy']);
    });

    // ===== Semua user login (data dibatasi di controller) =====
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('documents/{document}/file', [DocumentController::class, 'file'])->name('documents.file');
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'show']);
    Route::resource('legalisir', LegalisirController::class)->only(['index', 'show']);
    Route::resource('ijazah', PengambilanIjazahController::class)->only(['index', 'show']);
});
