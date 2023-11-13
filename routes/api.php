<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserGamataraController;
use App\Http\Controllers\api\ProjectManagerController;
use App\Http\Controllers\api\ProjectEngineerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//login
Route::post('login', [UserGamataraController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {

    //pm
    Route::prefix('pm')->group(function () {
        Route::get('list', [ProjectManagerController::class, 'index']);
        Route::get('detail-pm', [ProjectManagerController::class, 'detailPM']);
        Route::get('navbar-pm', [ProjectManagerController::class, 'navbarPM']);
        Route::get('subkategori-pm', [ProjectManagerController::class, 'subkategoriPM']);
        Route::get('pekerjaan-pm', [ProjectManagerController::class, 'pekerjaanPM']);
        Route::get('detailpekerjaan-pm', [ProjectManagerController::class, 'detailpekerjaanPM']);
        Route::post('approve', [ProjectManagerController::class, 'approve']);
    });

     //pe
     Route::prefix('pe')->group(function () {
        Route::get('list', [ProjectEngineerController::class, 'index']);
        Route::get('detail-pe', [ProjectEngineerController::class, 'detailPE']);
        Route::get('navbar-pe', [ProjectEngineerController::class, 'navbarPE']);
        Route::get('subkategori-pe', [ProjectEngineerController::class, 'subkategoriPE']);
        Route::get('pekerjaan-pe', [ProjectEngineerController::class, 'pekerjaanPE']);
        Route::get('detailpekerjaan-pe', [ProjectEngineerController::class, 'detailpekerjaanPE']);
    });
});

