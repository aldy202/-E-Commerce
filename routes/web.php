<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DahsboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\TestimoniController;
use Illuminate\Support\Facades\Route;


//auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('login_member', [AuthController::class, 'login_member']);
Route::post('login_member', [AuthController::class, 'login_member_action']);
Route::get('logout_member', [AuthController::class, 'logout_member']);

Route::get('register_member', [AuthController::class, 'register_member']);
Route::post('register_member', [AuthController::class, 'register_member_action']);


// kategori
Route::get('/kategori', [CategoryController::class, 'list']);
Route::get('/subkategori', [SubcategoryController::class, 'list']);
Route::get('/slider', [SliderController::class, 'list']);
Route::get('/barang', [ProductController::class, 'list']);
Route::get('/testimoni', [TestimoniController::class, 'list']);
Route::get('/review', [ReviewController::class, 'list']);

Route::get('/pesanan/baru', [OrderController::class, 'list']);
Route::get('/pesanan/konfirmasi', [OrderController::class, 'konfirmasi_list']);
Route::get('/pesanan/dikirim', [OrderController::class, 'kirim_list']);
Route::get('/pesanan/diterima', [OrderController::class, 'terima_list']);
Route::get('/pesanan/dikemas', [OrderController::class, 'kemas_list']);
Route::get('/pesanan/selesai', [OrderController::class, 'selesai_list']);

Route::get('/dashboard', [DahsboardController::class, 'index']);

Route::get('/payment', [PaymentController::class, 'list']);

Route::get('/laporan', [ReportController::class, 'index']);
Route::get('/tentang', [TentangController::class, 'index']);
Route::post('/tentang/{about}', [TentangController::class, 'update']);

//route homw
Route::get('/', [HomeController::class, 'index']);
Route::get('/products/{category}', [HomeController::class, 'products']);
Route::get('/product/{id}', [HomeController::class, 'product']);
Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/checkout', [HomeController::class, 'checkout']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/faq', [HomeController::class, 'faq']);

Route::post('/add', [HomeController::class, 'add_to_cart']);
Route::get('/delete_from_cart/{cart}', [HomeController::class, 'delete_from_cart']);
Route::get('/get_kota/{id}', [HomeController::class, 'get_kota']);
Route::get('/get_ongkir/{destination}/{weight}', [HomeController::class, 'get_ongkir']);
Route::get('/orders', [HomeController::class, 'orders']);


Route::post('/checkout_orders', [HomeController::class, 'checkout_order']);
Route::post('/payments', [HomeController::class, 'payment']);
Route::post('/pesanan_selesai/{order}', [HomeController::class, 'pesanan_selesai']);


