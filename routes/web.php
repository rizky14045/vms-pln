<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/register-visitor', [HomeController::class, 'registerVisitor'])->name('register-visitor');


Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/login','viewLogin')->name('login');
    Route::get('/register','viewRegister')->name('register');
});
