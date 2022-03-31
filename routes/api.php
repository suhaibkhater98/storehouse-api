<?php

use App\Http\Controllers\v1\CategoriesController;
use App\Http\Controllers\v1\DashboardsController;
use App\Http\Controllers\v1\ProductsController;
use App\Http\Controllers\v1\UsersController;
use App\Http\Controllers\v1\NewPasswordController;
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


Route::post('users/register',[UsersController::class  , 'register'])->name('user.login');
Route::post('users/login',[UsersController::class  , 'login'])->name('user.register');
Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('categories' , CategoriesController::class);
    Route::apiResource('products' , ProductsController::class);
    Route::apiResource('users' , UsersController::class);

    Route::post('products/decQuantity' , [ProductsController::class , 'decQuantity'])->name('decrease.product');
    Route::post('users/logout',[UsersController::class  , 'logout'])->name('user.logout');;

    Route::get('dashboards/getCountTotal' , [DashboardsController::class , 'getCountTotal'])->name('get.total');
});
