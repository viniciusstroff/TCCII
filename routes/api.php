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
    Route::apiResource('audits', AuditController::class);
    Route::apiResource('reports-pending', ReportPendingController::class);
    // Route::apiResource('reports', ReportController::class);

    Route::get('/reports', 'App\Http\Controllers\Api\ReportController@index');
    Route::get('/reports/{id}', 'App\Http\Controllers\Api\ReportController@show');
    Route::post('/reports/save', 'App\Http\Controllers\Api\ReportController@store');
    Route::put('/reports/{id}/update', 'App\Http\Controllers\Api\ReportController@update');
    Route::delete('/reports/{id}/remove', 'App\Http\Controllers\Api\ReportController@destroy');
    Route::post('/reports/search', 'App\Http\Controllers\Api\ReportController@search');
    Route::post('/reports/audit', 'App\Http\Controllers\Api\ReportController@audit');
    Route::post('/reports/teste', 'App\Http\Controllers\Api\ReportController@teste');

    Route::get('/reports-pending', 'App\Http\Controllers\Api\ReportPendingController@index');
    Route::post('/reports-pending/audit', 'App\Http\Controllers\Api\ReportPendingController@audit');
    Route::get('/reports-pending/finished/{id}', 'App\Http\Controllers\Api\ReportPendingController@finishedReport');
    Route::post('/reports-pending/search', 'App\Http\Controllers\Api\ReportPendingController@search');



    // Route::apiResource('reports-pending', ReportPendingController::class);
    
    // Route::apiResource('lighthouse', LighthouseController::class);
    // Route::prefix('audits')->group(function () {
    //     Route::get('reports', [ReportController::class, 'index']);
    //     Route::get('report/{id}', [ReportController::class , 'show']); 
    // });
    
});


