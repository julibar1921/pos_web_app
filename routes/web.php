<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::get('/products/{product}/label', [App\Http\Controllers\ProductController::class, 'printLabel'])->name('products.label');
    Route::post('/products/import', [App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::post('/expense-categories', [App\Http\Controllers\ExpenseController::class, 'storeCategory'])->name('expense-categories.store');
    Route::post('/customers/{customer}/repay', [App\Http\Controllers\CustomerController::class, 'repay'])->name('customers.repay');
    
    Route::get('/pos', [App\Http\Controllers\OrderController::class, 'index'])->name('pos.index');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders-today', [App\Http\Controllers\OrderController::class, 'todaySales'])->name('orders.today');
    Route::post('/orders/{order}/refund', [App\Http\Controllers\OrderController::class, 'refund'])->name('orders.refund');
    Route::get('/orders/refunds', [App\Http\Controllers\OrderController::class, 'refundsIndex'])->name('orders.refunds');
    Route::get('/orders/{order}/print', [App\Http\Controllers\OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders-history', [App\Http\Controllers\OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/reports/sales', [App\Http\Controllers\OrderController::class, 'salesReport'])->name('reports.sales');

    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/restock', [App\Http\Controllers\StockController::class, 'restockAssistant'])->name('stock.restock');
    Route::post('/products/{product}/stock', [App\Http\Controllers\StockController::class, 'adjust'])->name('stock.adjust');

    Route::get('/closings', [App\Http\Controllers\ClosingController::class, 'index'])->name('closings.index');
    Route::post('/closings', [App\Http\Controllers\ClosingController::class, 'store'])->name('closings.store');
    
    Route::get('/settings/export/products', [App\Http\Controllers\SettingController::class, 'exportProducts'])->name('settings.export.products');
    Route::get('/settings/export/orders', [App\Http\Controllers\SettingController::class, 'exportOrders'])->name('settings.export.orders');
    Route::get('/settings/backup', [App\Http\Controllers\SettingController::class, 'downloadBackup'])->name('settings.backup');
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});
