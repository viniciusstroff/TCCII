<?php

use App\Http\Controllers\Api\Audits\AuditController;
use App\Http\Controllers\Api\ReportPendingController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportScoreController;
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
    Route::apiResource('audits', AuditController::class);
    Route::apiResource('reports-pending', ReportPendingController::class);
    // Route::apiResource('reports', ReportController::class);

    // Route::get('/reports', 'App\Http\Controllers\Api\ReportController@index');
    
    Route::get('/reports/report/{report_id}/scores', 'App\Http\Controllers\Api\ReportScoreController@show');
    Route::get('/reports/report/{id}', 'App\Http\Controllers\Api\ReportController@show');
    Route::post('/reports/save', 'App\Http\Controllers\Api\ReportController@store');
    Route::put('/reports/{id}/update', 'App\Http\Controllers\Api\ReportController@update');
    Route::delete('/reports/{id}/remove', 'App\Http\Controllers\Api\ReportController@destroy');
    Route::post('/reports/search', 'App\Http\Controllers\Api\ReportController@search');
    Route::post('/reports/audit', 'App\Http\Controllers\Api\ReportController@audit');





    // Route::apiResource('reports-pending', ReportPendingController::class);
    
    // Route::apiResource('lighthouse', LighthouseController::class);
    // Route::prefix('audits')->group(function () {
    //     Route::get('reports', [ReportController::class, 'index']);
    //     Route::get('report/{id}', [ReportController::class , 'show']); 
    // });
    
});


