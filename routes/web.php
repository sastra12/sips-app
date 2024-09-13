<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\WasteBankController;
use App\Models\WasteBank;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'authView'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Manajemen Desa
    Route::get('/village/data', [VillageController::class, 'data'])->name('village.data');
    Route::resource('village', VillageController::class);


    // Manajemen Pengguna
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);

    // Manajemen Waste Bank
    Route::get('/waste-bank/data', [WasteBankController::class, 'data'])->name('waste-bank.data');
    Route::resource('waste-bank', WasteBankController::class);

    // Manajemen Customer
    Route::get('/customer/data', [CustomerController::class, 'data'])->name('customer.data');
    Route::resource('customer', CustomerController::class);
    Route::get('/waste-customer-details', [CustomerController::class, 'wasteCustomerDetails'])->name('waste-cust-details');
    Route::get('/waste-customer-data', [CustomerController::class, 'wasteCustData'])->name('waste-cust-data');
});
