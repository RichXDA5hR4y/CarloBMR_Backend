<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// ==================== PUBLIC ROUTES (Tidak perlu login) ====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// ==================== PROTECTED ROUTES (Harus login) ====================
Route::middleware('auth:sanctum')->group(function () {
    // 👇 User (Customer) Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // 👇 Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
    });
    
    // 👇 Order Routes (Customer)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::post('/{id}/payment-proof', [OrderController::class, 'updatePaymentProof']);
    });
    
    // 👇 Payment Routes (Customer)
    Route::get('/payments/info', [PaymentController::class, 'getPaymentInfo']);
    
    // 👇 Reports (Customer)
    Route::get('/reports/best-selling-products', [ReportController::class, 'bestSellingProducts']);
    Route::get('/reports/low-stock-products', [ReportController::class, 'lowStockProducts']);
    Route::get('/reports/sales', [ReportController::class, 'salesReport']);
    Route::get('/reports/category-performance', [ReportController::class, 'categoryPerformance']);
    
    // ==================== ADMIN & CASHIER ONLY ROUTES ====================
    Route::middleware('role:admin,cashier')->group(function () {
        // 👇 Category Routes
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });
        
        // 👇 Product Routes
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
        
        // 👇 Order Routes (Admin)
        Route::prefix('admin/orders')->group(function () {
            Route::get('/', [OrderController::class, 'adminIndex']);
            Route::put('/{id}/status', [OrderController::class, 'adminUpdateStatus']);
            Route::put('/{id}/payment-status', [OrderController::class, 'adminUpdatePaymentStatus']);
        });
        
        // 👇 Payment Routes (Admin)
        Route::prefix('payments')->group(function () {
            Route::post('/{order_id}', [PaymentController::class, 'store']);
            Route::put('/{id}', [PaymentController::class, 'update']);
        });
    });
    
    // ==================== ADMIN ONLY ROUTES ====================
    Route::middleware('role:admin')->group(function () {
        // 👇 Tambahkan routes admin-only di sini
        // Contoh: Route::post('/users', [UserController::class, 'store']);
    });
});

