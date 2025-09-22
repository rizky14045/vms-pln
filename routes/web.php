<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DeviceController;

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
Route::get('/register-employee', [HomeController::class, 'registerVisitor'])->name('register-visitor');
Route::post('/register-request', [HomeController::class, 'registerRequest'])->name('register-request');


Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/login','viewLogin')->name('login');
    Route::post('/login','login')->name('login.post');
    Route::get('/register','viewRegister')->name('register');
});

Route::controller(AreaController::class)->group(function () {
    Route::get('/areas','index')->name('areas.index');
    Route::get('/areas/create','create')->name('areas.create');
    Route::post('/areas','store')->name('areas.store');
    Route::get('/areas/{id}/edit','edit')->name('areas.edit');
    Route::put('/areas/{id}','update')->name('areas.update');
});

Route::controller(DeviceController::class)->group(function () {
    Route::get('/devices','index')->name('devices.index');
    Route::get('/devices/create','create')->name('devices.create');
    Route::post('/devices','store')->name('devices.store');
    Route::get('/devices/{id}/edit','edit')->name('devices.edit');
    Route::put('/devices/{id}','update')->name('devices.update');
});
