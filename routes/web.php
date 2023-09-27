<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;

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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

//customer
Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
Route::post('/updated/{id}', [CustomerController::class, 'updated'])->name('customer.updated');
Route::get('/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');

