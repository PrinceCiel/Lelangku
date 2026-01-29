<?php

use App\Http\Controllers\Backend\BarangController;
use App\Http\Controllers\Backend\DatadiriController;
use App\Http\Controllers\Backend\KategoriController;
use App\Http\Controllers\Backend\LelangController;
use App\Http\Controllers\Backend\PemenangController;
use App\Http\Controllers\Backend\ReviewBidController;
use App\Http\Controllers\Backend\StrukController as BackendStrukController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SingleController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================
// FRONTEND ROUTES
// ============================================

// Homepage
Route::get('/', [FrontController::class, 'index'])->name('awal');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.user');

// Search
Route::get('/search', [FrontController::class, 'search'])->name('search');

// Kategori
Route::get('kategori/{slug}', [FrontController::class, 'show'])->name('kategori.show');

// Lelang
Route::get('/lelang/{kode}/poll', [SingleController::class, 'poll']);
Route::get('/lelang/{kode}/history', [SingleController::class, 'bidHistory']);
Route::resource('lelang', SingleController::class);


// Profile
Route::get('/profile/dashboard', [FrontController::class, 'dashboard'])->name('dashboard.user');
Route::get('/profile/personal', [FrontController::class, 'personal'])->name('personal.user');
// ============================================
// STRUK & PAYMENT ROUTES
// ============================================

// Struk Detail (Frontend)
Route::get('struk/{kodestruk}', [StrukController::class, 'struk'])
    ->name('struk.detail'); // Ubah name agar konsisten

// Check Status Pembayaran Manual
Route::post('/struk/check-status/{kode}', [StrukController::class, 'checkStatus'])
    ->name('check.status'); // Pindahkan ke StrukController

// ============================================
// MIDTRANS ROUTES
// ============================================

// Midtrans Notification Handler (Webhook)
Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])
    ->name('midtrans.notification');

// Midtrans Redirect Handler (After Payment)
Route::get('/midtrans/finish', [MidtransController::class, 'handleRedirect'])
    ->name('midtrans.finish');

// Hapus route duplikat
// Route::get('/midtrans/redirect', [MidtransController::class, 'handleRedirect'])->name('midtrans.redirect');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Auth::routes();

// Registration & Verification
Route::resource('verifikasi', VerifikasiController::class);
Route::resource('daftar', RegisterController::class);

// ============================================
// USER ROUTES (Authenticated)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Deposit
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');

    // Struk Resource
    Route::resource('struk', SingleController::class);
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::group([
    'prefix' => 'admin',
    'as' => 'backend.',
    'middleware' => ['auth', IsAdmin::class]
], function () {

    // Dashboard
    Route::get('/', [BackendController::class, 'index'])->name('home');

    // Verifikasi User
    Route::get('verifikasi', [DatadiriController::class, 'index'])->name('verifikasi.index');
    Route::post('verifikasi/{id}/approve', [DatadiriController::class, 'approve'])->name('verifikasi.approve');
    Route::post('verifikasi/{id}/reject', [DatadiriController::class, 'reject'])->name('verifikasi.reject');

    // Master Data
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('lelang', LelangController::class);

    // Bid Review
    Route::resource('bid', ReviewBidController::class);

    // Struk Management
    Route::resource('struk', BackendStrukController::class);

    // Manual Payment (Jika ada transfer manual)
    Route::get('struk/bayar/{kode}', [BackendStrukController::class, 'bayar'])
        ->name('struk.bayar');
    Route::get('struk/status-paid/{kode}', [BackendStrukController::class, 'setPaid'])
        ->name('struk.setPaid');

    // Pemenang
    Route::get('pemenang', [PemenangController::class, 'index'])->name('pemenang');
});
