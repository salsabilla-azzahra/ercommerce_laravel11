<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\FlashSaleController;

// -------------------- Guest Routes (untuk pengguna yang belum login) --------------------

Route::group(['middleware' => 'guest'], function() {
    // Halaman utama, bisa berupa welcome page atau landing page
    Route::get('/', function() {
        return view('welcome');
    });

    // Halaman registrasi dan login
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/post-register', [AuthController::class, 'post_register'])->name('post.register');
    Route::post('/post-login', [AuthController::class, 'login'])->middleware('guest');
});


// -------------------- Admin Routes --------------------

Route::group(['middleware' => 'admin'], function() {

    // Dashboard Admin
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Routes untuk manajemen produk
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/admin/product/detail/{id}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product', [ProductController::class, 'index'])->name('admin.product');

    // Logout Admin
    Route::get('/admin-logout', [AuthController::class, 'admin_logout'])->name('admin.logout');
});


// -------------------- User Routes --------------------

Route::group(['middleware' => 'web'], function() {

    // Dashboard User
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');

    // Halaman detail produk untuk user
    Route::get('/user/product/detail/{id}', [UserController::class, 'detail_product'])->name('user.detail.product');

    // Proses pembelian produk oleh user
    Route::get('/product/purchase/{productId}/{userId}', [UserController::class, 'purchase']);

    // Logout User
    Route::get('/user-logout', [AuthController::class, 'user_logout'])->name('user.logout');
});


// -------------------- Flash Sale Routes --------------------

// Routes untuk fitur Flash Sale, hanya bisa diakses oleh pengguna yang sudah login
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::post('/post-login', [AuthController::class, 'login'])->middleware('guest');

    // Halaman Tambah Flash Sale
    Route::get('/flash-sale/create', [FlashSaleController::class, 'create'])->name('flash-sale.create');

    // Proses penyimpanan data Flash Sale
    Route::post('/flash-sale', [FlashSaleController::class, 'store'])->name('flash-sale.store');

    // Halaman Daftar Flash Sale
    Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');
});