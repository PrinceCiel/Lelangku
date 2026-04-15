<?php

use App\Http\Controllers\api\DepositController;
use App\Http\Controllers\api\FrontController;
use App\Http\Controllers\api\ItemSubmissionController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\SingleController;
use App\Http\Controllers\api\StrukController;
use App\Http\Controllers\api\VerifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

$mediaHeaders = [
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
    'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
    'Vary' => 'Origin',
];

Route::options('/media/{path}', function () use ($mediaHeaders) {
    return response('', 204, $mediaHeaders);
})->where('path', '.*');

Route::get('/media/{path}', function (string $path) use ($mediaHeaders) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->file(storage_path('app/public/' . $path), $mediaHeaders);
})->where('path', '.*');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// halaman home
Route::get('/home', [FrontController::class, 'index']);
Route::get('/kategori/{slug}', [FrontController::class, 'showKategori']);
Route::post('/search', [FrontController::class, 'search']);
// halaman single
Route::get('/lelang/{kode}', [SingleController::class, 'single']);
Route::post('/lelang/{kode}/bid', [SingleController::class, 'storebid']);
Route::get('/lelang/{kode}/poll', [SingleController::class, 'poll']);
Route::get('/lelang/{kode}/history', [SingleController::class, 'bidHistory']);
// halaman verifikasi data diri
Route::get('/verifikasi', [VerifikasiController::class, 'index']);
Route::post('/verifikasi/store', [VerifikasiController::class, 'storeVerifikasi']);
// halaman deposit
Route::post('/deposit/create', [DepositController::class, 'createDeposit'])->middleware('auth:sanctum');
Route::get('/deposit/{kode}', [DepositController::class, 'showDeposit'])->middleware('auth:sanctum');
// halaman profile
Route::get('/profile/dashboard', [ProfileController::class, 'dashboard'])->middleware('auth:sanctum');
Route::get('/profile/detail', [ProfileController::class, 'personal'])->middleware('auth:sanctum');
// halaman lelang yang dimenangkan
Route::get('/riwayatlelang', [StrukController::class, 'index'])->middleware('auth:sanctum');
Route::get('/riwayatlelang/{kode}', [StrukController::class, 'show'])->middleware('auth:sanctum');
// halaman ajuan barang
Route::get('/ajuan', [ItemSubmissionController::class, 'index'])->middleware('auth:sanctum');
Route::post('/ajuan', [ItemSubmissionController::class, 'store'])->middleware('auth:sanctum');
Route::get('/ajuan/{id}', [ItemSubmissionController::class, 'show'])->middleware('auth:sanctum');
