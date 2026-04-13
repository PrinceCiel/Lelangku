<?php

use App\Http\Controllers\SubmissionAdminController;
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
use App\Http\Controllers\AjuanController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\GagalbayarController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\ItemSubmissionController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================
// FRONTEND ROUTES
// ============================================

// Homepage
Route::get('/', [FrontController::class, 'index'])->name('awal');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.user');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ajuan
Route::middleware('auth')->prefix('ajukan-barang')->name('submissions.')->group(function () {
    Route::get('/',        [ItemSubmissionController::class, 'index'])->name('index');   // Pengajuan Saya
    Route::get('/buat',    [ItemSubmissionController::class, 'create'])->name('create'); // Form ajukan
    Route::post('/',       [ItemSubmissionController::class, 'store'])->name('store');   // Simpan
    Route::get('/{submission}', [ItemSubmissionController::class, 'show'])->name('show'); // Detail
});

// Search
Route::get('/search', [FrontController::class, 'search'])->name('search');

// Kategori
Route::get('kategori/{slug}', [FrontController::class, 'show'])->name('kategori.show');

// Lelang
Route::resource('lelang', SingleController::class);
Route::get('/lelang/{kode}/poll', [SingleController::class, 'poll']);
Route::get('/lelang/{kode}/history', [SingleController::class, 'bidHistory']);


// Profile
Route::get('/profile/dashboard', [FrontController::class, 'dashboard'])->name('dashboard.user');
Route::get('/profile/personal', [FrontController::class, 'personal'])->name('personal.user');


// ============================================
// MIDTRANS ROUTES
// ============================================

Route::post('/midtrans/notification', [MidtransCallbackController::class, 'notificationHandler'])
    ->name('midtrans.notification');

// Midtrans Redirect Handler (After Payment)
Route::get('/midtrans/finish', [MidtransCallbackController::class, 'handleRedirect'])
    ->name('midtrans.finish');

// Hapus route duplikat
// Route::get('/midtrans/redirect', [MidtransController::class, 'handleRedirect'])->name('midtrans.redirect');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Registration & Verification
Route::resource('verifikasi', VerifikasiController::class);
Route::resource('daftar', RegisterController::class);

// ============================================
// USER ROUTES (Authenticated)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Deposit
    Route::post('/deposit/create', [DepositController::class, 'create'])->name('deposit.create');
    Route::get('/deposit/{kodeDeposit}', [DepositController::class, 'show'])->name('deposit.show');

    // Midtrans webhook deposit (tanpa auth, Midtrans yang hit ini)

    // Struk Resource
    Route::get('struk', [SingleController::class, 'index'])
        ->name('struk.index'); // Ubah name agar konsisten
    // ============================================
    // STRUK & PAYMENT ROUTES
    // =========4===================================

    // Struk Detail (Frontend)
    Route::get('struk/{kodestruk}', [StrukController::class, 'struk'])
        ->name('struk.detail'); // Ubah name agar konsisten

    // Check Status Pembayaran Manual
    Route::post('/struk/check-status/{kode}', [StrukController::class, 'checkStatus'])
        ->name('check.status'); // Pindahkan ke StrukController

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
    Route::prefix('submissions')->name('submissions.')->group(function () {
        Route::get('/',                             [SubmissionAdminController::class, 'index'])->name('index');
        Route::get('/{submission}',                 [SubmissionAdminController::class, 'show'])->name('show');
        Route::post('/{submission}/status',         [SubmissionAdminController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{submission}/mark-purchased', [SubmissionAdminController::class, 'markAsPurchased'])->name('markAsPurchased');
    });
    // Verifikasi User
    Route::get('verifikasi', [DatadiriController::class, 'index'])->name('verifikasi.index');
    Route::post('verifikasi/{id}/approve', [DatadiriController::class, 'approve'])->name('verifikasi.approve');
    Route::post('verifikasi/{id}/reject', [DatadiriController::class, 'reject'])->name('verifikasi.reject');

    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('users/{id}/ban', [UserManagementController::class, 'ban'])->name('users.ban');
    Route::post('users/{id}/unban', [UserManagementController::class, 'unban'])->name('users.unban');
    Route::post('users/{id}/suspicious', [UserManagementController::class, 'suspicious'])->name('users.suspicious');
    Route::post('users/{id}/strike', [UserManagementController::class, 'tambahStrike'])->name('users.strike');

    // Master Data
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('lelang', LelangController::class);

    // Bid Review
    Route::prefix('bid')->name('bid.')->group(function () {
        Route::get('/',                                   [ReviewBidController::class, 'index'])->name('index');
        Route::get('/user-detail/{userId}/{lelangId}',    [ReviewBidController::class, 'userDetail'])->name('user.detail');
        Route::post('/ban/{userId}',                      [ReviewBidController::class, 'banUser'])->name('ban');
        Route::post('/unban/{userId}',                    [ReviewBidController::class, 'unbanUser'])->name('unban');
        Route::post('/suspicious/{userId}',               [ReviewBidController::class, 'markSuspicious'])->name('suspicious');
        Route::post('/remove/{userId}/{lelangId}',        [ReviewBidController::class, 'removeFromLelang'])->name('remove');
        Route::post('/cancel-bid/{bidId}',                [ReviewBidController::class, 'cancelBid'])->name('cancel.bid');
    });

    // Struk Management
    Route::resource('struk', BackendStrukController::class);

    // Manual Payment (Jika ada transfer manual)
    Route::get('struk/bayar/{kode}', [BackendStrukController::class, 'bayar'])
        ->name('struk.bayar');
    Route::get('struk/status-paid/{kode}', [BackendStrukController::class, 'setPaid'])
        ->name('struk.setPaid');
    Route::get('pembayaran/belum-bayar', [StrukController::class, 'belumBayar'])->name('struk.belum-bayar');
    Route::patch('pembayaran/{kode}/konfirmasi', [StrukController::class, 'konfirmasi'])
        ->name('struk.konfirmasi');

    Route::patch('pembayaran/{kode}/batal', [StrukController::class, 'batal'])
        ->name('struk.batal');
    // Pemenang
    Route::get('pemenang', [PemenangController::class, 'index'])->name('pemenang');


    // Gagal Bayar — Riwayat
    Route::get('gagal-bayar/riwayat', [GagalbayarController::class, 'riwayat'])
        ->name('gagalbayar.riwayat');
    Route::delete('gagal-bayar/riwayat/{kode}', [GagalBayarController::class, 'hapusStruk'])
        ->name('gagalbayar.hapus');

    // Gagal Bayar — Penyelesaian
    Route::get('gagal-bayar/penyelesaian', [GagalBayarController::class, 'penyelesaian'])
        ->name('gagalbayar.penyelesaian');
    Route::post('gagal-bayar/jadwal-ulang/{kode}', [GagalBayarController::class, 'jadwalUlang'])
        ->name('gagalbayar.jadwal-ulang');
    Route::post('gagal-bayar/alih/{kode}', [GagalBayarController::class, 'alihPemenang'])
        ->name('gagalbayar.alih');


    // Route untuk AJAX DataTables
    Route::get('/barang/data', [BarangController::class, 'getData'])->name('barang.data');
});

require __DIR__.'/auth.php';
