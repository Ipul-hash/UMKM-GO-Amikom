<?php

use App\Http\Controllers\Api\MasterData\ProdukController;
use App\Http\Controllers\Api\MasterData\StokController;
use App\Http\Controllers\Api\Operasional\KasirController;
use App\Http\Controllers\Api\Operasional\PesanOnlineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Master Data Routes: Produk & Stok
    Route::prefix('master-data')->group(function () {
        // Produk Endpoints
        Route::get('/produk/categories', [ProdukController::class, 'categories']);
        Route::get('/produk', [ProdukController::class, 'index']);
        Route::post('/produk', [ProdukController::class, 'store']);
        Route::get('/produk/{id}', [ProdukController::class, 'show']);
        Route::put('/produk/{id}', [ProdukController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

        // Stok & Inventory Endpoints
        Route::get('/stok', [StokController::class, 'index']);
        Route::get('/stok/summary', [StokController::class, 'summary']);
        Route::post('/stok/adjust', [StokController::class, 'adjust']);
        Route::get('/stok/movements', [StokController::class, 'movements']);
    });

    // Operasional Routes: POS / Kasir & Pesanan Online
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
    });
});
