<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\ThingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\IotController;
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

/*Route::group(['middleware' => 'cors'], function () {

});*/
Route::group(['middleware' => ['cors']], function(){
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);

    Route::post('/thing', [ThingController::class, 'create'])->middleware('auth:api');
    Route::post('/shop', [ShopController::class, 'store'])->middleware('auth:api');
    Route::get('/shop/isUserHas/{user}', [ShopController::class, 'isUserHasShop']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);

    Route::get('/shop', [ShopController::class, 'index']);
    Route::get('/shop/{id}', [ShopController::class, 'show']);
    Route::put('/shop/{id}', [ShopController::class, 'update'])->middleware('auth:api');

    Route::group(['prefix' => 'thing'], function () {
        Route::get('byUser/{id}', [ThingController::class, 'showByUser']);
        Route::get('/{id}', [ThingController::class, 'show']);
        Route::get('/', [ThingController::class, 'showAll']);
        Route::put('/{id}', [ThingController::class, 'update']);
        Route::delete('/{id}', [ThingController::class, 'delete']);
    });

    Route::get('/count', [ChartController::class, 'getThingsPerMonth'])->middleware('auth:api');

    Route::group(['prefix' => 'iot'], function () {
        Route::post('/setThingCoords', [IotController::class, 'setThingCoords']);
    });

});

