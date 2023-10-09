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
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SubkategoriController;
use App\Http\Controllers\SettingPekerjaanController;
use App\Http\Controllers\ProjectManagerController;

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
        Route::get('/export', [CustomerController::class, 'export'])->name('customer.export');
    });

    //user
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/updated/{id}', [UserController::class, 'updated'])->name('user.updated');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::get('/user', [UserController::class, 'export'])->name('user.export');
    });

    //role
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
        Route::get('/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::post('/updated/{id}', [RoleController::class, 'updated'])->name('role.updated');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');
        Route::get('/export', [RoleController::class, 'export'])->name('role.export');
    });

    //vendor
    Route::prefix('vendor')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('vendor');
        Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::get('/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::post('/updated/{id}', [VendorController::class, 'updated'])->name('vendor.updated');
        Route::get('/delete/{id}', [VendorController::class, 'delete'])->name('vendor.delete');
        Route::get('/export', [VendorController::class, 'export'])->name('vendor.export');
    });

    //pekerjaan
    Route::prefix('pekerjaan')->group(function () {
        Route::get('/', [PekerjaanController::class, 'index'])->name('pekerjaan');
        Route::get('/edit/{id}', [PekerjaanController::class, 'edit'])->name('pekerjaan.edit');
        Route::get('/create', [PekerjaanController::class, 'create'])->name('pekerjaan.create');
        Route::post('/store', [PekerjaanController::class, 'store'])->name('pekerjaan.store');
        Route::post('/updated/{id}', [PekerjaanController::class, 'updated'])->name('pekerjaan.updated');
        Route::get('/delete/{id}', [PekerjaanController::class, 'delete'])->name('pekerjaan.delete');
        Route::get('/export', [PekerjaanController::class, 'export'])->name('pekerjaan.export');
    });

    //On Request
    Route::prefix('on_request')->group(function () {
        Route::get('/', [OnRequestController::class, 'index'])->name('on_request');
        Route::get('/detail/{id}', [OnRequestController::class, 'detail'])->name('on_request.detail');
        Route::get('/create', [OnRequestController::class, 'create'])->name('on_request.create');
        Route::post('/store', [OnRequestController::class, 'store'])->name('on_request.store');
        Route::post('/updated/{id}', [OnRequestController::class, 'updated'])->name('on_request.updated');
        Route::get('/export', [OnRequestController::class, 'export'])->name('on_request.export');
        Route::get('/export-detail/{id}', [OnRequestController::class, 'exportDetail'])->name('on_request.exportDetail');
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

    //karyawan
    Route::prefix('karyawan')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('karyawan');
        Route::get('/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::get('/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::post('/updated/{id}', [KaryawanController::class, 'updated'])->name('karyawan.updated');
        Route::get('/delete/{id}', [KaryawanController::class, 'delete'])->name('karyawan.delete');
        Route::get('/export', [KaryawanController::class, 'export'])->name('karyawan.export');
    });

    //kategori
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori');
        Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');
        Route::post('/updated/{id}', [KategoriController::class, 'updated'])->name('kategori.updated');
        Route::get('/delete/{id}', [KategoriController::class, 'delete'])->name('kategori.delete');
        Route::get('/export', [KategoriController::class, 'export'])->name('kategori.export');
    });

    //sub_kategori
    Route::prefix('sub_kategori')->group(function () {
        Route::get('/', [SubkategoriController::class, 'index'])->name('sub_kategori');
        Route::get('/edit/{id}', [SubkategoriController::class, 'edit'])->name('sub_kategori.edit');
        Route::get('/create', [SubkategoriController::class, 'create'])->name('sub_kategori.create');
        Route::post('/store', [SubkategoriController::class, 'store'])->name('sub_kategori.store');
        Route::post('/updated/{id}', [SubkategoriController::class, 'updated'])->name('sub_kategori.updated');
        Route::get('/delete/{id}', [SubkategoriController::class, 'delete'])->name('sub_kategori.delete');
        Route::get('/export', [SubkategoriController::class, 'export'])->name('sub_kategori.export');
    });

    //settingpekerjaan
    Route::prefix('setting_pekerjaan')->group(function () {
        Route::get('/', [SettingPekerjaanController::class, 'index'])->name('setting_pekerjaan');
        Route::get('/edit/{id}', [SettingPekerjaanController::class, 'edit'])->name('setting_pekerjaan.edit');
        Route::get('/create', [SettingPekerjaanController::class, 'create'])->name('setting_pekerjaan.create');
        Route::post('/store', [SettingPekerjaanController::class, 'store'])->name('setting_pekerjaan.store');
        Route::post('/updated/{id}', [SettingPekerjaanController::class, 'updated'])->name('setting_pekerjaan.updated');
        Route::get('/delete/{id}', [SettingPekerjaanController::class, 'delete'])->name('setting_pekerjaan.delete');
        Route::get('/export', [SettingPekerjaanController::class, 'export'])->name('setting_pekerjaan.export');
    });

    //ProjectManager
    Route::prefix('project_manager')->group(function () {
        Route::get('/', [ProjectManagerController::class, 'index'])->name('project_manager');
        Route::get('/edit/{id}', [ProjectManagerController::class, 'edit'])->name('project_manager.edit');
        Route::get('/create', [ProjectManagerController::class, 'create'])->name('project_manager.create');
        Route::post('/store', [ProjectManagerController::class, 'store'])->name('project_manager.store');
        Route::post('/updated/{id}', [ProjectManagerController::class, 'updated'])->name('project_manager.updated');
        Route::get('/delete/{id}', [ProjectManagerController::class, 'delete'])->name('project_manager.delete');
        Route::get('/export', [ProjectManagerController::class, 'export'])->name('project_manager.export');
    });
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
    Route::get('/delete/{id}', [OnRequestController::class, 'delete'])->name('on_request.delete');
});

//On Progress
Route::prefix('on_progress')->group(function () {
    Route::get('/', [OnProgressController::class, 'index'])->name('on_progress');
    Route::get('/edit/{id}', [OnProgressController::class, 'edit'])->name('on_progress.edit');
    Route::get('/create', [OnProgressController::class, 'create'])->name('on_progress.create');
    Route::post('/store', [OnProgressController::class, 'store'])->name('on_progress.store');
    Route::post('/updated/{id}', [OnProgressController::class, 'updated'])->name('on_progress.updated');
    Route::get('/delete/{id}', [OnProgressController::class, 'delete'])->name('on_progress.delete');
    Route::get('request/{id}',[OnProgressController::class,'addWork'])->name('on_progres.work');
    Route::post('request/{id}',[OnProgressController::class,'requestPost'])->name('on_progres.work');
    Route::get('detail-worker',[OnProgressController::class,'detailWorker'])->name('on_progres.detail-worker');
    Route::get('sub-detail',[OnProgressController::class,'subDetailWorker'])->name('on_progres.sub-detail');

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
