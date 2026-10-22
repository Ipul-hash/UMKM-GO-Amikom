<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alias');
        Route::get('/pos', [DashboardController::class, 'pos'])->name('pos')->middleware('permission:pos.access');
        Route::get('/products', [DashboardController::class, 'products'])->name('products')->middleware('permission:products.view');
        Route::get('/inventory', [DashboardController::class, 'inventory'])->name('inventory')->middleware('permission:inventory.view');
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders')->middleware('permission:orders.view');
        Route::get('/finance', [DashboardController::class, 'finance'])->name('finance')->middleware('permission:finance.view');
        Route::get('/customers', [DashboardController::class, 'customers'])->name('customers')->middleware('permission:customers.view');
        Route::get('/promo', [DashboardController::class, 'promo'])->name('promo')->middleware('permission:promo.manage');
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports')->middleware('permission:reports.view');
        Route::get('/employees', [DashboardController::class, 'employees'])->name('employees')->middleware('permission:employees.manage');
        Route::get('/suppliers', [DashboardController::class, 'suppliers'])->name('suppliers')->middleware('permission:suppliers.manage');
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings')->middleware('permission:settings.manage');
    });
});
