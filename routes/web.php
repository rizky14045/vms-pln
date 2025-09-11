<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index']);


Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/testing','addData')->name('index');
    Route::get('/login','viewLogin')->name('login');
    Route::get('/register','viewRegister')->name('register');
});

Route::controller(AreaController::class)->group(function () {
    Route::get('/test-area','index')->name('area.index');
});
