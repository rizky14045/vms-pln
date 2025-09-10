<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware(['throttle:api'])->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/register','register')->name('register');
        Route::post('/login','login')->name('login');
        Route::post('/refresh','refresh')->name('refresh'); 
        Route::middleware(['auth:api'])->group(function () {
            Route::post('/profile','me')->name('me'); 
            Route::post('/logout','logout')->name('logout'); 
        });
    });
});
