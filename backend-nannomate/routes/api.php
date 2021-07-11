<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarDataPenelitianController;
use App\Http\Controllers\DaftarMenungguVerifikasiController;
use App\Http\Controllers\DaftarDataDiterimaController;
use App\Http\Controllers\DaftarDataDitolakController;
use App\Http\Controllers\SpesiesController;
use App\Http\Controllers\UmurGeologiController;
use App\Http\Controllers\AkunAdminController;
use App\Http\Controllers\DetailSampelController;
use App\Http\Controllers\CountSampelController;
use App\Http\Controllers\TerimaSampelController;
use App\Http\Controllers\TolakSampelController;
use App\Http\Controllers\ExcelDBInputController;
use App\Http\Controllers\ExcelRequestInputController;
use App\Http\Controllers\PDFDBInputController;
use App\Http\Controllers\PDFRequestInputController;
use App\Http\Controllers\JPGDBInputController;
use App\Http\Controllers\JPGRequestInputController;
use App\Http\Controllers\PNGDBInputController;
use App\Http\Controllers\PNGRequestInputController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/daftardatapenelitian', [DaftarDataPenelitianController::class, 'index']);
Route::get('/spesies/{id_spesies}', [SpesiesController::class, 'show']);
Route::get('/umur_geologi/{id_umur}', [UmurGeologiController::class, 'show']);
Route::get('/detail_sampel/{id_sample}', [DetailSampelController::class, 'show']);
Route::get('/exceldb/{id_sample}', [ExcelDBInputController::class, 'export']);
Route::get('/pdfdb/{id_sample}', [PDFDBInputController::class, 'export']);
Route::get('/jpgdb/{id_sample}', [JPGDBInputController::class, 'export']);
Route::get('/pngdb/{id_sample}', [PNGDBInputController::class, 'export']);

//test routes
Route::get('/test/{id_sample}', [ExcelDBInputController::class, 'test']);
Route::post('/excelrequesttest', [ExcelRequestInputController::class, 'test']);

//logged in user routes
Route::middleware(['middleware' => 'auth:api'])->group(function () {
    Route::post('/detail_sampel', [DetailSampelController::class, 'store']);
    Route::put('/detail_sampel/{id_sample}', [DetailSampelController::class, 'update']);
    Route::get('/daftarmenungguverifikasiuser', [DaftarMenungguVerifikasiController::class, 'bagiUser']);
    Route::get('/daftardataditerimauser', [DaftarDataDiterimaController::class, 'bagiUser']);
    Route::get('/daftardataditolakuser', [DaftarDataDitolakController::class, 'bagiUser']);
    Route::post('/excelrequest', [ExcelRequestInputController::class, 'export']);
    Route::post('/pdfrequest', [PDFRequestInputController::class, 'export']);
    Route::post('/jpgrequest', [JPGRequestInputController::class, 'export']);
    Route::post('/pngrequest', [PNGRequestInputController::class, 'export']);
});


//admin routes
Route::middleware(['middleware' => 'auth:api'])->group(function () {
    Route::get('/countmenungguverifikasi', [CountSampelController::class, 'countMenungguVerifikasi']);
    Route::get('/countditerima', [CountSampelController::class, 'countDiterima']);
    Route::get('/countditolak', [CountSampelController::class, 'countDitolak']);
    Route::get('/daftarmenungguverifikasiadmin', [DaftarMenungguVerifikasiController::class, 'bagiAdmin']);
    Route::get('/daftardataditerimaadmin', [DaftarDataDiterimaController::class, 'bagiAdmin']);
    Route::get('/daftardataditolakadmin', [DaftarDataDitolakController::class, 'bagiAdmin']);
    Route::post('/spesies', [SpesiesController::class, 'store']);
    Route::put('/spesies/{id_spesies}', [SpesiesController::class, 'update']);
    Route::delete('/spesies/{id_spesies}', [SpesiesController::class, 'destroy']);
    Route::put('/terimasampel/{id_sample}', [TerimaSampelController::class, 'update']);
    Route::put('/tolaksampel/{id_sample}', [TolakSampelController::class, 'update']);
    Route::get('/umur_geologi', [UmurGeologiController::class, 'index']);
});

//logged in user & admin routes
Route::middleware(['middleware' => 'auth:api'])->group(function () {
    Route::get('/auth/user', [AuthController::class, 'details']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::delete('/detail_sampel/{id_sample}', [DetailSampelController::class, 'destroy']);
    Route::get('/spesies', [SpesiesController::class, 'index']);
});


//super admin routes
Route::get('/akunadmin', [AkunAdminController::class, 'index']);
Route::get('/akunadmin/{id_user}', [AkunAdminController::class, 'show']);
Route::post('/akunadmin', [AkunAdminController::class, 'store']);
Route::put('/akunadmin/{id_user}', [AkunAdminController::class, 'update']);
Route::delete('/akunadmin/{id_user}', [AkunAdminController::class, 'destroy']);
