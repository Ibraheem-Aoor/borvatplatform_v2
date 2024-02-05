<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
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


Route::group(['middleware' => 'guest:sanctum'] , function(){
    Route::post('login' , [AuthController::class , 'login']);
});


Route::group(['middleware' => 'auth:sanctum'] , function(){
    Route::apiResource('product' , ProductController::class)->withoutMiddleware(ThrottleRequests::class);
});
