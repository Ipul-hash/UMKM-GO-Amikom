<?php

use App\Http\Controllers\Api\Laporan\KeuanganController;
use App\Http\Controllers\Api\Laporan\LaporanController;
use App\Http\Controllers\Api\MasterData\ProdukController;
use App\Http\Controllers\Api\MasterData\StokController;
use App\Http\Controllers\Api\Operasional\KaryawanController;
use App\Http\Controllers\Api\Operasional\KasirController;
use App\Http\Controllers\Api\Operasional\PelangganController;
use App\Http\Controllers\Api\Operasional\PengaturanController;
use App\Http\Controllers\Api\Operasional\PesanOnlineController;
use App\Http\Controllers\Api\Operasional\PromoDiskonController;
use App\Http\Controllers\Api\Operasional\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('master-data')->group(function () {
        Route::get('/produk/categories', [ProdukController::class, 'categories']);
        Route::get('/produk', [ProdukController::class, 'index']);
        Route::post('/produk', [ProdukController::class, 'store']);
        Route::get('/produk/{id}', [ProdukController::class, 'show']);
        Route::put('/produk/{id}', [ProdukController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

        Route::get('/stok', [StokController::class, 'index']);
        Route::get('/stok/summary', [StokController::class, 'summary']);
        Route::post('/stok/adjust', [StokController::class, 'adjust']);
        Route::get('/stok/movements', [StokController::class, 'movements']);
    });

    Route::prefix('operasional')->group(function () {
        Route::prefix('kasir')->group(function () {
            Route::get('/catalog', [KasirController::class, 'catalog']);
            Route::get('/customers', [KasirController::class, 'customers']);
            Route::get('/shift', [KasirController::class, 'activeShift']);
            Route::post('/checkout', [KasirController::class, 'checkout']);
            Route::get('/receipt/{id}', [KasirController::class, 'receipt']);
        });

        Route::prefix('pesanan-online')->group(function () {
            Route::get('/couriers', [PesanOnlineController::class, 'couriers']);
            Route::get('/', [PesanOnlineController::class, 'index']);
            Route::get('/{id}', [PesanOnlineController::class, 'show']);
            Route::post('/{id}/accept', [PesanOnlineController::class, 'accept']);
            Route::post('/{id}/ship', [PesanOnlineController::class, 'ship']);
            Route::post('/{id}/complete', [PesanOnlineController::class, 'complete']);
            Route::post('/{id}/cancel', [PesanOnlineController::class, 'cancel']);
        });

        Route::prefix('promo-diskon')->group(function () {
            Route::get('/', [PromoDiskonController::class, 'index']);
            Route::post('/', [PromoDiskonController::class, 'store']);
            Route::get('/{id}', [PromoDiskonController::class, 'show']);
            Route::put('/{id}', [PromoDiskonController::class, 'update']);
            Route::delete('/{id}', [PromoDiskonController::class, 'destroy']);
            Route::patch('/{id}/toggle-status', [PromoDiskonController::class, 'toggleStatus']);
        });

        Route::prefix('pelanggan')->group(function () {
            Route::get('/', [PelangganController::class, 'index']);
            Route::post('/', [PelangganController::class, 'store']);
            Route::get('/{id}', [PelangganController::class, 'show']);
            Route::put('/{id}', [PelangganController::class, 'update']);
            Route::delete('/{id}', [PelangganController::class, 'destroy']);
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/categories', [SupplierController::class, 'categories']);
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
        });

        Route::prefix('karyawan')->group(function () {
            Route::get('/', [KaryawanController::class, 'index']);
            Route::post('/', [KaryawanController::class, 'store']);
            Route::get('/shifts', [KaryawanController::class, 'shifts']);
            Route::get('/{id}', [KaryawanController::class, 'show']);
            Route::put('/{id}', [KaryawanController::class, 'update']);
            Route::delete('/{id}', [KaryawanController::class, 'destroy']);
            Route::post('/{id}/assign-shift', [KaryawanController::class, 'assignShift']);
        });

        Route::prefix('pengaturan')->group(function () {
            Route::get('/', [PengaturanController::class, 'show']);
            Route::post('/', [PengaturanController::class, 'update']);
            Route::put('/', [PengaturanController::class, 'update']);
            Route::post('/upload-logo', [PengaturanController::class, 'uploadLogo']);
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::prefix('keuangan')->group(function () {
            Route::get('/categories', [KeuanganController::class, 'categories']);
            Route::get('/', [KeuanganController::class, 'index']);
            Route::post('/', [KeuanganController::class, 'store']);
            Route::get('/{id}', [KeuanganController::class, 'show']);
            Route::put('/{id}', [KeuanganController::class, 'update']);
            Route::delete('/{id}', [KeuanganController::class, 'destroy']);
        });

        Route::prefix('penjualan')->group(function () {
            Route::get('/', [LaporanController::class, 'index']);
            Route::get('/export-csv', [LaporanController::class, 'exportCsv']);
        });

        Route::get('/export-csv', [LaporanController::class, 'exportCsv']);
    });
});
