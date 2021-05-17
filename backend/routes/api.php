<?php

use App\Http\Controllers\API\SimulationsController;
use App\Http\Controllers\API\UserController;
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

Route::group(['namespace' => 'API'], static function (): void {
    Route::post('signup', [UserController::class, 'store']);

    Route::group(['middleware' => 'auth:api'], static function (): void {
        Route::get('simulations', [SimulationsController::class, 'index']);
    });
});
