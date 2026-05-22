<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NfcController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ========================================
// AUTHENTICATED ROUTES (All Users)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - konten berbeda berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile - semua user bisa edit profil sendiri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================================
// MAHASISWA ONLY ROUTES (Students)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Rekap absensi pribadi
    Route::get('/my-attendance', [UserController::class, 'showMyAttendance'])->name('users.my-attendance');

    // QR Code pribadi - mahasiswa bisa lihat dan generate QR mereka
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        Route::get('/my-code', [QrCodeController::class, 'myQrCode'])->name('my-code');
    });
})->middleware('student'); // Middleware untuk memastikan hanya mahasiswa, tidak admin

// ========================================
// ADMIN ONLY ROUTES
// ========================================
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // ---- SCANNER ROUTES (Admin only) ----

    // NFC Scanner (Android devices)
    Route::prefix('nfc')->name('nfc.')->group(function () {
        Route::get('/scan', function () {
            return view('nfc.scan');
        })->name('scan');
        Route::post('/scan', [NfcController::class, 'scan'])->name('scan.submit');

        // Registrasi Kartu NFC
        Route::get('/register', function () {
            return view('nfc.register');
        })->name('register.form');
        Route::post('/register', [NfcController::class, 'register'])->name('register');
    });

    // QR Code Scanner (All devices including iOS)
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        Route::get('/scan', [QrCodeController::class, 'scan'])->name('scan');
        Route::post('/scan', [QrCodeController::class, 'scanSubmit'])->name('scan.submit');

        // Download QR Code milik mahasiswa tertentu
        Route::get('/download/{userId}', [QrCodeController::class, 'download'])->name('download');
    });

    // ---- MANAGEMENT ROUTES (Admin only) ----

    // Manajemen Data Mahasiswa
    Route::prefix('students')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Data Absensi
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/today', [AttendanceController::class, 'today'])->name('today');
        Route::get('/stats', [AttendanceController::class, 'stats'])->name('stats');
        Route::get('/export', [AttendanceController::class, 'export'])->name('export');
    });
});

require __DIR__ . '/auth.php';
