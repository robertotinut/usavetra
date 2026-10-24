<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/upgrade', [App\Http\Controllers\HomeController::class, 'upgrade'])->name('upgrade');
    
    Route::resource('categories', App\Http\Controllers\CategoryController::class)->except(['create', 'edit']);
    Route::middleware(['owner'])->group(function () {
        Route::resource('suppliers', App\Http\Controllers\SupplierController::class)->except(['create', 'edit']);
        Route::resource('purchases', App\Http\Controllers\PurchaseController::class)->except(['edit', 'update']);
        Route::resource('employees', App\Http\Controllers\EmployeeController::class)->only(['index', 'store', 'destroy']);
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
        
        // Asisten AI UMKM (Smart Analytics)
        Route::get('/smart-assistant', [App\Http\Controllers\SmartAssistantController::class, 'index'])->name('smart-assistant.index');
        
        // Kalkulator Bisnis HPP
        Route::get('/calculator/hpp', [App\Http\Controllers\CalculatorController::class, 'hpp'])->name('calculator.hpp');
        
        // Hutang-Piutang
        Route::resource('debts', App\Http\Controllers\DebtController::class)->except(['create', 'edit', 'update']);
        Route::post('/debts/{debt}/pay', [App\Http\Controllers\DebtController::class, 'pay'])->name('debts.pay');
    });
    
    // Buku Kas Harian (Kasir & Owner bisa akses)
    Route::resource('cashflow', App\Http\Controllers\CashFlowController::class)->only(['index', 'store', 'destroy']);
    
    // Kasir tetap bisa melihat index produk (tapi di view harga belinya disembunyikan)
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    
    // Print struk
    Route::get('sales/{sale}/print', [App\Http\Controllers\SaleController::class, 'print'])->name('sales.print');
    Route::resource('sales', App\Http\Controllers\SaleController::class)->except(['edit', 'update', 'destroy']);
    
    Route::resource('pending-products', App\Http\Controllers\PendingProductController::class)->only(['index', 'create', 'store']);
    
    // Profil UMKM
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profiles.index');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profiles.update');

    // Profil Akun Pengguna (User)
    Route::get('/account/profile', [App\Http\Controllers\AccountController::class, 'index'])->name('account.profile');
    Route::post('/account/profile', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');

    // ==========================================
    // SUPER ADMIN ROUTES
    // ==========================================
    Route::middleware(['super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Kelola Paket SaaS
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
        
        // Kelola Pelanggan (Tenants/Stores)
        Route::get('/tenants', [App\Http\Controllers\Admin\TenantController::class, 'index'])->name('tenants.index');
        Route::post('/tenants', [App\Http\Controllers\Admin\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{store}', [App\Http\Controllers\Admin\TenantController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{store}/subscribe', [App\Http\Controllers\Admin\TenantController::class, 'subscribe'])->name('tenants.subscribe');
        Route::post('/tenants/{store}/unsubscribe', [App\Http\Controllers\Admin\TenantController::class, 'unsubscribe'])->name('tenants.unsubscribe');
        
        // Manajemen Pengguna
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
});
