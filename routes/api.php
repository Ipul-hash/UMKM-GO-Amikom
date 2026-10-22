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
        Route::get('/produk/categories', [ProdukController::class, 'categories'])->middleware('permission:products.view');
        Route::get('/produk', [ProdukController::class, 'index'])->middleware('permission:products.view');
        Route::post('/produk', [ProdukController::class, 'store'])->middleware('permission:products.manage');
        Route::get('/produk/{id}', [ProdukController::class, 'show'])->middleware('permission:products.view');
        Route::put('/produk/{id}', [ProdukController::class, 'update'])->middleware('permission:products.manage');
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->middleware('permission:products.delete');

        Route::get('/stok', [StokController::class, 'index'])->middleware('permission:inventory.view');
        Route::get('/stok/summary', [StokController::class, 'summary'])->middleware('permission:inventory.view');
        Route::post('/stok/adjust', [StokController::class, 'adjust'])->middleware('permission:inventory.manage');
        Route::get('/stok/movements', [StokController::class, 'movements'])->middleware('permission:inventory.view');
    });

    Route::prefix('operasional')->group(function () {
        Route::prefix('kasir')->middleware('permission:pos.access')->group(function () {
            Route::get('/catalog', [KasirController::class, 'catalog']);
            Route::get('/customers', [KasirController::class, 'customers']);
            Route::get('/shift', [KasirController::class, 'activeShift']);
            Route::post('/checkout', [KasirController::class, 'checkout']);
            Route::get('/receipt/{id}', [KasirController::class, 'receipt']);
        });

        Route::prefix('pesanan-online')->group(function () {
            Route::get('/couriers', [PesanOnlineController::class, 'couriers'])->middleware('permission:orders.view');
            Route::get('/', [PesanOnlineController::class, 'index'])->middleware('permission:orders.view');
            Route::get('/{id}', [PesanOnlineController::class, 'show'])->middleware('permission:orders.view');
            Route::post('/{id}/accept', [PesanOnlineController::class, 'accept'])->middleware('permission:orders.process');
            Route::post('/{id}/ship', [PesanOnlineController::class, 'ship'])->middleware('permission:orders.ship');
            Route::post('/{id}/complete', [PesanOnlineController::class, 'complete'])->middleware('permission:orders.process');
            Route::post('/{id}/cancel', [PesanOnlineController::class, 'cancel'])->middleware('permission:orders.process');
        });

        Route::prefix('promo-diskon')->middleware('permission:promo.manage')->group(function () {
            Route::get('/', [PromoDiskonController::class, 'index']);
            Route::post('/', [PromoDiskonController::class, 'store']);
            Route::get('/{id}', [PromoDiskonController::class, 'show']);
            Route::put('/{id}', [PromoDiskonController::class, 'update']);
            Route::delete('/{id}', [PromoDiskonController::class, 'destroy']);
            Route::patch('/{id}/toggle-status', [PromoDiskonController::class, 'toggleStatus']);
        });

        Route::prefix('pelanggan')->group(function () {
            Route::get('/', [PelangganController::class, 'index'])->middleware('permission:customers.view');
            Route::post('/', [PelangganController::class, 'store'])->middleware('permission:customers.manage');
            Route::get('/{id}', [PelangganController::class, 'show'])->middleware('permission:customers.view');
            Route::put('/{id}', [PelangganController::class, 'update'])->middleware('permission:customers.manage');
            Route::delete('/{id}', [PelangganController::class, 'destroy'])->middleware('permission:customers.manage');
        });

        Route::prefix('supplier')->middleware('permission:suppliers.manage')->group(function () {
            Route::get('/categories', [SupplierController::class, 'categories']);
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
        });

        Route::prefix('karyawan')->middleware('permission:employees.manage')->group(function () {
            Route::get('/permissions', [KaryawanController::class, 'permissions']);
            Route::get('/', [KaryawanController::class, 'index']);
            Route::post('/', [KaryawanController::class, 'store']);
            Route::get('/shifts', [KaryawanController::class, 'shifts']);
            Route::get('/{id}', [KaryawanController::class, 'show']);
            Route::put('/{id}', [KaryawanController::class, 'update']);
            Route::delete('/{id}', [KaryawanController::class, 'destroy']);
            Route::post('/{id}/assign-shift', [KaryawanController::class, 'assignShift']);
        });

        Route::prefix('pengaturan')->middleware('permission:settings.manage')->group(function () {
            Route::get('/', [PengaturanController::class, 'show']);
            Route::post('/', [PengaturanController::class, 'update']);
            Route::put('/', [PengaturanController::class, 'update']);
            Route::post('/upload-logo', [PengaturanController::class, 'uploadLogo']);
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::prefix('keuangan')->group(function () {
            Route::get('/categories', [KeuanganController::class, 'categories'])->middleware('permission:finance.view');
            Route::get('/', [KeuanganController::class, 'index'])->middleware('permission:finance.view');
            Route::post('/', [KeuanganController::class, 'store'])->middleware('permission:finance.manage');
            Route::get('/{id}', [KeuanganController::class, 'show'])->middleware('permission:finance.view');
            Route::put('/{id}', [KeuanganController::class, 'update'])->middleware('permission:finance.manage');
            Route::delete('/{id}', [KeuanganController::class, 'destroy'])->middleware('permission:finance.manage');
        });

        Route::prefix('penjualan')->middleware('permission:reports.view')->group(function () {
            Route::get('/', [LaporanController::class, 'index']);
            Route::get('/export-csv', [LaporanController::class, 'exportCsv']);
        });

        Route::get('/export-csv', [LaporanController::class, 'exportCsv'])->middleware('permission:reports.view');
    });
});
