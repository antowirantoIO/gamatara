<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserGamataraController;
use App\Http\Controllers\api\ProjectManagerController;

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
    Route::prefix('pm')->group(function () {
        Route::get('list', [ProjectManagerController::class, 'index']);
        Route::get('detail-pm', [ProjectManagerController::class, 'detailPM']);
        Route::get('navbar-pm', [ProjectManagerController::class, 'navbarPM']);
    });
});
//project
