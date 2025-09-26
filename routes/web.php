<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisteredController;
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
Route::get('/register-employee', [HomeController::class, 'registerVisitor'])->name('register-visitor');
Route::post('/register-request', [HomeController::class, 'registerRequest'])->name('register-request');
Route::get('/base64', [HomeController::class, 'testBase']);


Route::middleware('guest')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/login','viewLogin')->name('login');
        Route::post('/login','login')->name('login.post');
        Route::get('/register','viewRegister')->name('register');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
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
    Route::controller(RegisteredController::class)->group(function () {
        Route::get('/registered','index')->name('registered.index');
        Route::get('/registered/{id}','show')->name('registered.show');
        Route::get('/registered/{id}/approve','approve')->name('registered.approve');
        Route::patch('/registered/{id}/approve','updateApprove')->name('registered.update-approve');
        Route::get('/registered/create','create')->name('registered.create');
        Route::post('/registered','store')->name('registered.store');
        Route::get('/registered/{id}/edit','edit')->name('registered.edit');
        Route::put('/registered/{id}','update')->name('registered.update');
    });
});