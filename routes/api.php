<?php

use App\Http\Controllers\Api\Audits\AuditController;
use App\Http\Controllers\Api\Audits\LighthouseController;
use App\Http\Controllers\Api\ReportPendingController;
use App\Http\Controllers\Api\ReportController;
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


// Route::middleware('auth:api')->group( function () {

// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('api')->group(function () {
    Route::apiResource('lighthouse', LighthouseController::class);
    Route::apiResource('audits', AuditController::class);
    Route::apiResource('reports-pending', ReportPendingController::class);
    Route::apiResource('reports', ReportController::class);


    // Route::prefix('audits')->group(function () {
    //     Route::get('reports', [ReportController::class, 'index']);
    //     Route::get('report/{id}', [ReportController::class , 'show']); 
    // });
    
});


