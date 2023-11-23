<?php

use App\Http\Controllers\KontakApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[UserController::class, 'register'])->name('api.register');
Route::post('/login', [UserController::class, 'login'])->name('api.logout');;
//
Route::group(['middleware'=>['auth.jwt']],function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('api.getuser');;
    Route::post('/logout',[UserController::class, 'logout'])->name('api.logout');;        
    //
    Route::apiResource('/kontak',KontakApiController::class);
    Route::get('/provinsi',[WilayahApiController::class,'getProvinsi'])->name('api.provinsi');
    Route::get('/kabupaten/{prov_id}',[WilayahApiController::class,'getKabupatan'])->name('api.kabupaten');
    Route::get('/kecamatan/{kab_id}',[WilayahApiController::class,'getKecamatan'])->name('api.kecamatan');
    Route::get('/desa/{kec_id}',[WilayahApiController::class,'getDesa'])->name('api.desa');
});
