<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\VisitorCardController;
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
Route::get('/get-data', [DashboardController::class, 'testGetData']);
Route::get('/register-employee', [HomeController::class, 'registerEmployee'])->name('register-employee');
Route::post('/register-request', [HomeController::class, 'registerRequest'])->name('register-request');
Route::get('/register-visitors', [HomeController::class, 'registerVisitor'])->name('register-visitor');
Route::post('/register-visitor-request', [HomeController::class, 'registerVisitorRequest'])->name('register-visitor-request');
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
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile');
    Route::put('/change-password', [MyProfileController::class, 'changePassword'])->name('change-password');
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

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products','index')->name('products.index');
        Route::get('/products/export','export')->name('products.export');
        Route::get('/products/create','create')->name('products.create');
        Route::post('/products','store')->name('products.store');
        Route::get('/products/{id}/edit','edit')->name('products.edit');
        Route::put('/products/{id}','update')->name('products.update');
    });

    Route::controller(VisitorCardController::class)->group(function () {
        Route::get('/visitor-cards','index')->name('visitor-cards.index');
        Route::get('/visitor-cards/create','create')->name('visitor-cards.create');
        Route::post('/visitor-cards','store')->name('visitor-cards.store');
        Route::get('/visitor-cards/histories','histories')->name('visitor-cards.histories');
        Route::get('/visitor-cards/histories/export','historyExport')->name('visitor-cards.histories.export');
        Route::post('/visitor-cards/return','returnCards')->name('visitor-cards.return');
        Route::get('/visitor-cards/{id}/edit','edit')->name('visitor-cards.edit');
        Route::put('/visitor-cards/{id}','update')->name('visitor-cards.update');
    });

    Route::controller(RegisteredController::class)->group(function () {
        Route::get('/registered/datas','getDataIndex')->name('registered.data');
        Route::get('/registered','index')->name('registered.index');
        Route::get('/registered/export','export')->name('registered.export');
        Route::get('/registered-visitor','indexVisitor')->name('registered.index.visitor');
        Route::get('/registered-visitor/export','exportVisitor')->name('registered.export.visitor');
        Route::get('/registered-visitor/create-visitor','createVisitor')->name('registered.create-visitor');
        Route::post('/registered-visitor','storeVisitor')->name('registered.store-visitor');
        Route::get('/registered-visitor/{id}/edit','editVisitor')->name('registered.edit-visitor');
        Route::patch('/registered/{id}/edit-card-visitor','updateCardVisitor')->name('registered.update-card-visitor');
        Route::get('/registered/{id}','show')->name('registered.show');
        Route::get('/registered/{id}/approve','approve')->name('registered.approve');
        Route::get('/registered/{id}/approve-visitor','approveVisitor')->name('registered.approve.visitor');
        Route::get('/registered/{id}/edit','edit')->name('registered.edit');
        Route::patch('/registered/{id}/approve','updateApprove')->name('registered.update-approve');
        Route::patch('/registered/{id}/approve-visitor','updateApproveVisitor')->name('registered.update-approve-visitor');
        Route::patch('/registered/{id}/edit-card','updateCard')->name('registered.update-card');
        Route::patch('/registered/{id}/delete','delete')->name('registered.delete');
        Route::put('/registered/{id}','update')->name('registered.update');
    });
});