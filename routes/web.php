<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\OnRequestController;
use App\Http\Controllers\OnProgressController;
use App\Http\Controllers\CompleteController;
use App\Http\Controllers\KeluhanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

    //customer
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::post('/updated/{id}', [CustomerController::class, 'updated'])->name('customer.updated');
        Route::get('/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    });

    //user
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/updated/{id}', [UserController::class, 'updated'])->name('user.updated');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    });

    //role
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
        Route::get('/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::post('/updated/{id}', [RoleController::class, 'updated'])->name('role.updated');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');
    });

    //vendor
    Route::prefix('vendor')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('vendor');
        Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::get('/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::post('/updated/{id}', [VendorController::class, 'updated'])->name('vendor.updated');
        Route::get('/delete/{id}', [VendorController::class, 'delete'])->name('vendor.delete');
    });

    //pekerjaan
    Route::prefix('pekerjaan')->group(function () {
        Route::get('/', [PekerjaanController::class, 'index'])->name('pekerjaan');
        Route::get('/edit/{id}', [PekerjaanController::class, 'edit'])->name('pekerjaan.edit');
        Route::get('/create', [PekerjaanController::class, 'create'])->name('pekerjaan.create');
        Route::post('/store', [PekerjaanController::class, 'store'])->name('pekerjaan.store');
        Route::post('/updated/{id}', [PekerjaanController::class, 'updated'])->name('pekerjaan.updated');
        Route::get('/delete/{id}', [PekerjaanController::class, 'delete'])->name('pekerjaan.delete');
    });

    //On Request
    Route::prefix('on_request')->group(function () {
        Route::get('/', [OnRequestController::class, 'index'])->name('on_request');
        Route::get('/detail/{id}', [OnRequestController::class, 'detail'])->name('on_request.detail');
        Route::get('/create', [OnRequestController::class, 'create'])->name('on_request.create');
        Route::post('/store', [OnRequestController::class, 'store'])->name('on_request.store');
        Route::post('/updated/{id}', [OnRequestController::class, 'updated'])->name('on_request.updated');
    });

    //On Progress
    Route::prefix('on_progress')->group(function () {
        Route::get('/', [OnProgressController::class, 'index'])->name('on_progress');
        Route::get('/edit/{id}', [OnProgressController::class, 'edit'])->name('on_progress.edit');
        Route::get('/create', [OnProgressController::class, 'create'])->name('on_progress.create');
        Route::post('/store', [OnProgressController::class, 'store'])->name('on_progress.store');
        Route::post('/updated/{id}', [OnProgressController::class, 'updated'])->name('on_progress.updated');
        Route::get('/delete/{id}', [OnProgressController::class, 'delete'])->name('on_progress.delete');
    });

    //complete
    Route::prefix('complete')->group(function () {
        Route::get('/', [CompleteController::class, 'index'])->name('complete');
        Route::get('/edit/{id}', [CompleteController::class, 'edit'])->name('complete.edit');
        Route::get('/create', [CompleteController::class, 'create'])->name('complete.create');
        Route::post('/store', [CompleteController::class, 'store'])->name('complete.store');
        Route::post('/updated/{id}', [CompleteController::class, 'updated'])->name('complete.updated');
        Route::get('/delete/{id}', [CompleteController::class, 'delete'])->name('complete.delete');
    });

    //keluhan
    Route::prefix('keluhan')->group(function () {
        Route::get('/{id}', [KeluhanController::class, 'delete'])->name('keluhan.delete');
        Route::post('/store/{id}', [KeluhanController::class, 'store'])->name('keluhan.store');
    });
});
