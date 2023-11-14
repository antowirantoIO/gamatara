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
use App\Http\Controllers\LaporanCustomerController;
use App\Http\Controllers\LaporanVendorController;
use App\Http\Controllers\LaporanProjectManagerController;
use App\Http\Controllers\SatisfactionNoteController;
use App\Http\Controllers\LokasiProjectController;
use App\Http\Controllers\JenisKapalController;
use App\Http\Controllers\OnProgressExportController;
use App\Http\Controllers\CompleteExportController;

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

Route::get('/unauthorized', function() {
    return response()->json([
        'status' => false,
        'message' => 'unauthorized'
    ]);
})->name('unauthorized');

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
        Route::get('/export', [UserController::class, 'export'])->name('user.export');
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
        Route::get('/table-data/{id}', [OnRequestController::class, 'tableData'])->name('on_request.tableData');
        Route::get('/detail/{id}', [OnRequestController::class, 'detail'])->name('on_request.detail');
        Route::get('/create', [OnRequestController::class, 'create'])->name('on_request.create');
        Route::post('/store', [OnRequestController::class, 'store'])->name('on_request.store');
        Route::post('/updated/{id}', [OnRequestController::class, 'updated'])->name('on_request.updated');
        Route::get('/export', [OnRequestController::class, 'export'])->name('on_request.export');
        Route::get('/export-detail/{id}', [OnRequestController::class, 'exportDetail'])->name('on_request.exportDetail');
    });

    //keluhan
    Route::prefix('keluhan')->group(function () {
        Route::get('/{id}', [KeluhanController::class, 'delete'])->name('keluhan.delete');
        Route::post('/store/{id}', [KeluhanController::class, 'store'])->name('keluhan.store');
        Route::get('/getData/{id}', [KeluhanController::class, 'getData'])->name('keluhan.getData');
        Route::post('/approve/{id}', [KeluhanController::class, 'approve'])->name('keluhan.approve');
        Route::get('/spk/{id}', [KeluhanController::class, 'SPK'])->name('keluhan.spk');
        Route::get('/satuan/{id}', [KeluhanController::class, 'SPKSatuan'])->name('keluhan.satuan');
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
        Route::get('kategori-setting',[SettingPekerjaanController::class,'getKategori'])->name('setting_pekerjaan.kategori');
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

    //laporan customer
    Route::prefix('laporan_customer')->group(function () {
        Route::get('/', [LaporanCustomerController::class, 'index'])->name('laporan_customer');
        Route::get('/detail/{id}', [LaporanCustomerController::class, 'detail'])->name('laporan_customer.detail');
        Route::get('/detail_customer/{id}', [LaporanCustomerController::class, 'detailCustomer'])->name('laporan_customer_detail.detail');
        Route::get('/export', [LaporanCustomerController::class, 'export'])->name('laporan_customer.export');
        Route::get('/detail_export', [LaporanCustomerController::class, 'export'])->name('laporan_detail_customer.export');
        Route::get('/detail_project_export', [LaporanCustomerController::class, 'export'])->name('laporan_detail_project_customer.export');
        Route::get('/chart', [LaporanCustomerController::class, 'chart'])->name('laporan_customer.chart');
    });

    //laporan vendor
    Route::prefix('laporan_vendor')->group(function () {
        Route::get('/', [LaporanVendorController::class, 'index'])->name('laporan_vendor');
        Route::get('/detail/{id}', [LaporanVendorController::class, 'detail'])->name('laporan_vendor.detail');
        Route::get('/export', [LaporanVendorController::class, 'export'])->name('laporan_vendor.export');
        Route::prefix('chart')->group(function(){
            Route::get('data',[LaporanVendorController::class,'chart'])->name('laporan_vendor.charts');
        });
    });

    //laporan project manager
    Route::prefix('laporan_project_manager')->group(function () {
        Route::get('/', [LaporanProjectManagerController::class, 'index'])->name('laporan_project_manager');
        Route::get('/detail/{id}', [LaporanProjectManagerController::class, 'detail'])->name('laporan_project_manager.detail');
        Route::get('/export', [LaporanProjectManagerController::class, 'export'])->name('laporan_project_manager.export');

        Route::prefix('chart')->group(function(){
            Route::get('data',[LaporanProjectManagerController::class,'chart'])->name('laporan_project_manager.charts');
        });
    });

    //laporan Satisfaction note
    Route::prefix('satisfaction_note')->group(function () {
        Route::get('/', [SatisfactionNoteController::class, 'index'])->name('satisfaction_note');
        Route::get('/export', [SatisfactionNoteController::class, 'export'])->name('satisfaction_note.export');
    });

    //On Progress
    Route::prefix('on_progress')->group(function () {
        Route::get('/', [OnProgressController::class, 'index'])->name('on_progress');
        Route::get('/edit/{id}', [OnProgressController::class, 'edit'])->name('on_progress.edit');
        Route::get('/create', [OnProgressController::class, 'create'])->name('on_progress.create');
        Route::post('/store', [OnProgressController::class, 'store'])->name('on_progress.store');
        Route::post('/updated/{id}', [OnProgressController::class, 'updated'])->name('on_progress.updated');
        Route::get('/delete/{id}', [OnProgressController::class, 'delete'])->name('on_progress.delete');
        Route::get('sub-detail/{id}/{idProject}/{idSub}',[OnProgressController::class,'subDetailWorker'])->name('on_progres.sub-detail');
        Route::get('detail-worker/{id}',[OnProgressController::class,'detailWorker'])->name('on_progres.detail-worker');
        Route::get('table-data/{id}',[OnProgressController::class,'tableData'])->name('on_progres.table-data');
        Route::get('approval/{id}',[OnProgressController::class,'approvalProject'])->name('on_progres.approval-project');


        Route::prefix('pekerjaan-vendor')->group(function(){
            Route::get('all-data/{id}/{idProject}',[OnProgressController::class,'allPekerjaanVendor'])->name('on_progress.pekerjaan-vendor.all');
            Route::get('vendor-worker/{id}/{idProject}/{subKategori}/{idKategori}',[OnProgressController::class,'vendorWorker'])->name('on_progres.vendor-worker');
            Route::get('detail-vendor-worker/{id}',[OnProgressController::class,'detailVendorWorker'])->name('on_progres.detail-vendor-worker');
            Route::post('update',[OnProgressController::class,'updateVendorWork'])->name('on_progress.pekerjaan-vendor.update');
        });


        Route::prefix('request')->group(function(){
            Route::get('tambah-pekerjaan/{id}/{vendor}/{kategori}/{subkategori}',[OnProgressController::class,'addWork'])->name('on_progres.request-pekerjaan');
            Route::post('tambah-pekerjaan/{id}',[OnProgressController::class,'requestPost'])->name('on_progres.work');
            Route::get('tambah-kategori/{id}/{vendor}',[OnProgressController::class,'tambahKategori'])->name('on_progres.request.tambah-kategori');
            Route::post('tambah-kategori',[OnProgressController::class,'storeTambahKategori'])->name('on_progres.store-kategori');
            Route::post('delete-request',[OnProgressController::class,'deleteRequest'])->name('on_progres.request-delete');

        });

        Route::prefix('tagihan')->group(function(){
            Route::get('list/tagihan-vendor/{id}',[OnProgressController::class,'dataTagihan'])->name('on_progres.tagihan.all');
            Route::get('tagihan-vendor/{id}/{vendor}',[OnProgressController::class,'tagihanVendor'])->name('on_progres.tagihan-vendor');
            Route::get('tagihan-customer/{id}',[OnProgressController::class,'tagihanCustomer'])->name('on_progres.tagihan-customer');
        });

        Route::prefix('setting')->group(function(){
            Route::get('index/{id}',[OnProgressController::class,'setting'])->name('on_progres.setting');
            Route::get('estimasi/{id}',[OnProgressController::class,'settingEstimasi'])->name('setting.estimasi');
            Route::get('detail-estimasi/{id}/{idProjects}',[OnProgressController::class,'detailEstimasi'])->name('setting.estimasi.detail');
        });

        Route::prefix('ajax')->group(function(){
            Route::get('pekerjaan-vendor',[OnProgressController::class,'ajaxPekerjaanVendor'])->name('ajax.vendor');
            Route::get('progres-pekerjaan',[OnProgressController::class,'ajaxProgresPekerjaan'])->name('ajax.progres-pekerjaan');
            Route::get('progres-pekerjaan-vendor',[OnProgressController::class,'ajaxProgresPekerjaanVendor'])->name('ajax.progres-pekerjaan-vendor');
            Route::get('setting-estimasi',[OnProgressController::class,'ajaxSettingEstimasi'])->name('ajax.setting-estimasi');
            Route::get('tagihan-vendor',[OnProgressController::class,'ajaxTagihanVendor'])->name('ajax.tagihan-vendor');
            Route::get('tagihan-customer',[OnProgressController::class,'ajaxTagihanCustomer'])->name('ajax.tagihan-customer');
            Route::get('tagihan-all',[OnProgressController::class,'ajaxAllTagihan'])->name('ajax.tagiham-all');
            Route::get('sub-kategori/{id}',[OnProgressController::class,'getSubKategori'])->name('on_progres.sub-kategori');
            Route::get('pekerjaan',[OnProgressController::class,'getPekerjaan'])->name('on_progres.pekerjaan');
            Route::get('lokasi',[OnProgressController::class,'getLokasi'])->name('on_progres.lokasi');
            Route::get('unit/{id}',[OnProgressController::class,'ajaxUnitPekerjaan'])->name('ajax.unit-pekerjaan');
            Route::get('recent-activity',[OnProgressController::class,'ajaxActivityRecent'])->name('ajax.recent-activity');
            Route::get('edit/request/{id}',[OnProgressController::class,'editRequestPekerjaan'])->name('on_progres.request.edit');
            Route::post('update/estimasi',[OnProgressController::class,'updateEstimasiProject'])->name('ajax.update-estimasi-project');
        });

        Route::prefix('export')->group(function(){
            Route::get('data',[OnProgressExportController::class,'allData'])->name('on_progres.export-data');
            Route::get('pekerjaan-vendor',[OnProgressExportController::class,'pekerjaanVendor'])->name('on_progres.export-pekrjaan-vendor');
            Route::get('pekerjaan-onprogres',[OnProgressExportController::class,'dataPekerjaan'])->name('on_progress.export-pekerjaan');
            Route::get('all/tagihan-vendor',[OnProgressExportController::class,'allTagihanVendor'])->name('on_progres.export.all-tagihan-vendor');
            Route::get('tagihan-customer',[OnProgressExportController::class,'tagihanCustomer'])->name('on_progres.export.tagihan_customer');
        });


    });

    //complete
    Route::prefix('complete')->group(function () {
        Route::get('/', [CompleteController::class, 'index'])->name('complete');
        Route::get('/edit/{id}', [CompleteController::class, 'edit'])->name('complete.edit');
        Route::get('/create', [CompleteController::class, 'create'])->name('complete.create');
        Route::post('/store', [CompleteController::class, 'store'])->name('complete.store');
        Route::post('/updated/{id}', [CompleteController::class, 'updated'])->name('complete.updated');
        Route::get('/delete/{id}', [CompleteController::class, 'delete'])->name('complete.delete');

        Route::prefix('pekerjaan')->group(function(){
            Route::get('detail/{id}',[CompleteController::class,'detailPekerjaan'])->name('complete.pekerjaan');
            Route::get('sub-detail/{id}/{idProject}/{idSub}',[CompleteController::class,'subDetailPekerjaan'])->name('complete.sub-detail-pekerjaan');
        });

        Route::prefix('tagihan')->group(function(){
            Route::get('list/tagihan-vendor/{id}',[CompleteController::class,'dataTagihan'])->name('complete.tagihan.all');
            Route::get('vendor/{id}/{vendor}',[CompleteController::class,'tagihanVendor'])->name('complete.tagihan-vendor');
            Route::get('customer/{id}',[CompleteController::class,'tagihanCustomer'])->name('complete.tagihan-customer');
            Route::get('tagihan-all',[CompleteController::class,'ajaxAllTagihan'])->name('complete.ajax.tagiham-all');
        });

        Route::prefix('ajax')->group(function(){
            Route::get('pekerjaan',[CompleteController::class,'ajaxProgresPekerjaan'])->name('complete.ajax.progres-pekerjaan');
            Route::get('pekerjaan-vendor',[CompleteController::class,'ajaxPekerjaanVendor'])->name('complete.ajax.pekerjaan-vendor');
            Route::get('setting-estimasi',[CompleteController::class,'ajaxSettingEstimasi'])->name('complete.ajax.setting-estimasi');
            Route::get('tagihan-vendor',[CompleteController::class,'ajaxTagihanVendor'])->name('complete.ajax.tagihan-vendor');
            Route::get('tagihan-customer',[CompleteController::class,'ajaxTagihanCustomer'])->name('complete.ajax.tagihan-customer');
        });

        Route::prefix('export')->group(function(){
            Route::get('all-data',[CompleteExportController::class,'allData'])->name('complete.export.all');
            Route::get('pekerjaan-vendor',[CompleteExportController::class,'pekerjaanVendor'])->name('complete.export.pekrjaan-vendor');
            Route::get('pekerjaan-complete',[CompleteExportController::class,'dataPekerjaan'])->name('complete.export.pekerjaan');
        });

        Route::prefix('setting')->group(function(){
            Route::get('index/{id}',[CompleteController::class,'setting'])->name('complete.setting');
            Route::get('estimasi/{id}',[CompleteController::class,'settingEstimasi'])->name('complete.setting.estimasi');
            Route::get('detail-estimasi/{id}/{idProjects}',[CompleteController::class,'detailEstimasi'])->name('complete.setting.estimasi-detail');
        });

        Route::prefix('pekerjaan-vendor')->group(function(){
            Route::get('all-data/{id}/{idProject}',[CompleteController::class,'allPekerjaanVendor'])->name('complete.pekerjaan-vendor.all');
            Route::get('detail/{id}/{idProject}/{subKategori}/{idKategori}',[CompleteController::class,'pekerjaanVendor'])->name('complete.pekerjaan-vendor');
        });
    });

    //lokasi_project
    Route::prefix('lokasi_project')->group(function () {
        Route::get('/', [LokasiProjectController::class, 'index'])->name('lokasi_project');
        Route::get('/edit/{id}', [LokasiProjectController::class, 'edit'])->name('lokasi_project.edit');
        Route::get('/create', [LokasiProjectController::class, 'create'])->name('lokasi_project.create');
        Route::post('/store', [LokasiProjectController::class, 'store'])->name('lokasi_project.store');
        Route::post('/updated/{id}', [LokasiProjectController::class, 'updated'])->name('lokasi_project.updated');
        Route::get('/delete/{id}', [LokasiProjectController::class, 'delete'])->name('lokasi_project.delete');
        Route::get('/export', [LokasiProjectController::class, 'export'])->name('lokasi_project.export');
    });

    //jenis_kapal
    Route::prefix('jenis_kapal')->group(function () {
        Route::get('/', [JenisKapalController::class, 'index'])->name('jenis_kapal');
        Route::get('/edit/{id}', [JenisKapalController::class, 'edit'])->name('jenis_kapal.edit');
        Route::get('/create', [JenisKapalController::class, 'create'])->name('jenis_kapal.create');
        Route::post('/store', [JenisKapalController::class, 'store'])->name('jenis_kapal.store');
        Route::post('/updated/{id}', [JenisKapalController::class, 'updated'])->name('jenis_kapal.updated');
        Route::get('/delete/{id}', [JenisKapalController::class, 'delete'])->name('jenis_kapal.delete');
        Route::get('/export', [JenisKapalController::class, 'export'])->name('jenis_kapal.export');
    });
});
