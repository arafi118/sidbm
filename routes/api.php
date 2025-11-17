<?php

use App\Http\Controllers\Api\AngsuranController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BasisDataController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MobileActivationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/mobile-token-activation', [MobileActivationController::class, 'activation']);
Route::get('/ambil-data-lokasi', [MobileActivationController::class, 'ambilDataLokasi']);

Route::post('/auth', [AuthController::class, 'auth']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'tenant'], 'prefix' => 'v1'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/basis-data', [BasisDataController::class, 'index']);
    Route::get('/basis-data/desa', [BasisDataController::class, 'desa']);
    Route::get('/basis-data/penduduk', [BasisDataController::class, 'penduduk']);
    Route::get('/basis-data/kelompok', [BasisDataController::class, 'kelompok']);
    Route::get('/basis-data/lembaga_lain', [BasisDataController::class, 'lembagaLain']);

    Route::get('/angsuran/search', [AngsuranController::class, 'search']);
    Route::get('/angsuran/{pinjaman_id}', [AngsuranController::class, 'pinjaman']);
    Route::post('/angsuran', [AngsuranController::class, 'simpan']);
});
