<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alias');
    Route::get('/pos', [DashboardController::class, 'pos'])->name('pos');
    Route::get('/products', [DashboardController::class, 'products'])->name('products');
    Route::get('/inventory', [DashboardController::class, 'inventory'])->name('inventory');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/finance', [DashboardController::class, 'finance'])->name('finance');
    Route::get('/customers', [DashboardController::class, 'customers'])->name('customers');
    Route::get('/promo', [DashboardController::class, 'promo'])->name('promo');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/employees', [DashboardController::class, 'employees'])->name('employees');
    Route::get('/suppliers', [DashboardController::class, 'suppliers'])->name('suppliers');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});
