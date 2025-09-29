<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;

# Admin Controllers
use App\Http\Controllers\Admin\DashboardController as DashboardAdmin;
use App\Http\Controllers\Admin\ManageMaster\UserController as UserAdmin;
use App\Http\Controllers\Admin\ManageMaster\CategoryController as CategoryAdmin;
use App\Http\Controllers\Admin\ManageMaster\ProductController as ProductAdmin;
use App\Http\Controllers\Admin\ManageMaster\VoucherController as VoucherAdmin;
use App\Http\Controllers\Admin\TransactionController as TransactionAdmin;
# Sales Controllers
use App\Http\Controllers\Sales\DashboardController as DashboardSales;
use App\Http\Controllers\Sales\ManageMaster\CategoryController as CategorySales;
use App\Http\Controllers\Sales\ManageMaster\ProductController as ProductSales;
use App\Http\Controllers\Sales\TransactionController as TransactionSales;

/*
|--------------------------------------------------------------------------
| Web Routes develop by kuli it tecno
|--------------------------------------------------------------------------
*/

# -------------------- AUTH --------------------
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

# -------------------- Guest --------------------
Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/cart', [GuestController::class, 'showCart'])->name('cart.show');
Route::post('/cart/fetch', [GuestController::class, 'fetchCart'])->name('cart.fetch');
Route::get('/product/{slug}', [GuestController::class, 'showProduct'])->name('product.show');
Route::post('/checkout', [GuestController::class, 'checkout'])->name('checkout');
Route::post('/midtrans/callback', [GuestController::class, 'callback'])->name('midtrans.callback');
Route::get('/checkout-success', [GuestController::class, 'success'])->name('checkout.success');
Route::post('/voucher', [GuestController::class, 'voucher'])->name('checkout.voucher');

# -------------------- ADMIN --------------------
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    # Dashboard
    Route::get('/', [DashboardAdmin::class, 'index']);

    # Manage Data Member
    Route::prefix('manage-master')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserAdmin::class, 'index']);
            Route::post('/', [UserAdmin::class, 'create']);
            Route::get('all', [UserAdmin::class, 'getall']);
            Route::post('get', [UserAdmin::class, 'get']);
            Route::post('update', [UserAdmin::class, 'update']);
            Route::delete('/', [UserAdmin::class, 'delete']);
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryAdmin::class, 'index']);
            Route::post('/', [CategoryAdmin::class, 'create']);
            Route::get('all', [CategoryAdmin::class, 'getall']);
            Route::post('get', [CategoryAdmin::class, 'get']);
            Route::post('update', [CategoryAdmin::class, 'update']);
            Route::delete('/', [CategoryAdmin::class, 'delete']);
        });
        Route::prefix('voucher')->group(function () {
            Route::get('/', [VoucherAdmin::class, 'index']);
            Route::post('/', [VoucherAdmin::class, 'create']);
            Route::get('all', [VoucherAdmin::class, 'getall']);
            Route::post('get', [VoucherAdmin::class, 'get']);
            Route::post('update', [VoucherAdmin::class, 'update']);
            Route::delete('/', [VoucherAdmin::class, 'delete']);
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductAdmin::class, 'index']);
            Route::post('/', [ProductAdmin::class, 'create']);
            Route::get('all', [ProductAdmin::class, 'getall']);
            Route::post('get', [ProductAdmin::class, 'get']);
            Route::post('update', [ProductAdmin::class, 'update']);
            Route::delete('/', [ProductAdmin::class, 'delete']);
        });
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionAdmin::class, 'index']);
        Route::get('all', [TransactionAdmin::class, 'getall']);
        Route::get('print', [TransactionAdmin::class, 'print']);
        Route::get('show/{id}', [TransactionAdmin::class, 'show']);
    });
});

# -------------------- SALES --------------------
Route::prefix('sales')->middleware(['auth', 'role:sales'])->group(function () {
    # Dashboard
    Route::get('/', [DashboardSales::class, 'index']);

    # Manage Data Member
    Route::prefix('manage-master')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategorySales::class, 'index']);
            Route::post('/', [CategorySales::class, 'create']);
            Route::get('all', [CategorySales::class, 'getall']);
            Route::post('get', [CategorySales::class, 'get']);
            Route::post('update', [CategorySales::class, 'update']);
            Route::delete('/', [CategorySales::class, 'delete']);
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductSales::class, 'index']);
            Route::post('/', [ProductSales::class, 'create']);
            Route::get('all', [ProductSales::class, 'getall']);
            Route::post('get', [ProductSales::class, 'get']);
            Route::post('update', [ProductSales::class, 'update']);
            Route::delete('/', [ProductSales::class, 'delete']);
        });
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionSales::class, 'index']);
        Route::get('all', [TransactionSales::class, 'getall']);
        Route::get('print', [TransactionSales::class, 'print']);
        Route::get('show/{id}', [TransactionSales::class, 'show']);
    });
});
