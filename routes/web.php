<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PekerjaanController;

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

