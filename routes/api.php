<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TestimoniController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('admin', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group([
    'middleware' => 'api',
], function () {
    Route::resources([
        'categories' => CategoryController::class,
        'subcategories' => SubcategoryController::class,
        'slider' => SliderController::class,
        'products' => ProductController::class,
        'members' => MemberController::class,
        'testimonis' => TestimoniController::class,
        'reviews' => ReviewController::class,
        'orders' => OrderController::class,
        'payments' => PaymentController::class,
    ]);

    Route::get('pesanan/baru',[OrderController::class, 'baru']);
    Route::get('pesanan/konfirmasi',[OrderController::class, 'konfirmasi']);
    Route::get('pesanan/kemas',[OrderController::class, 'kemas']);
    Route::get('pesanan/kirim',[OrderController::class, 'kirim']);
    Route::get('pesanan/terima',[OrderController::class, 'terima']);
    Route::get('pesanan/selesai',[OrderController::class, 'selesai']);

    Route::post('pesanan/ubah_status/{order}',[OrderController::class, 'ubah_status']);
    Route::get('reports',[ReportController::class, 'index']);

    Route::get('reports',[ReportController::class, 'get_reports']);
});
