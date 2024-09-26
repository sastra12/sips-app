<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageCustomerByTPS3RController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\WasteBankController;
use App\Http\Controllers\WasteEntriController;
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
Route::view('/export-excel-by-month-view', 'export-excel.export-by-month');
Route::view('/export-excel-by-year', 'export-excel.export-by-year');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Route Admin YRPW
    Route::middleware(['checkRole:1'])->group(function () {
        // Manajemen Desa
        Route::get('/village/data', [VillageController::class, 'data'])->name('village.data');
        Route::resource('village', VillageController::class);

        // Manajemen Pengguna
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('user', UserController::class);

        // Manajemen Waste Bank
        Route::get('/waste-bank/data', [WasteBankController::class, 'data'])->name('waste-bank.data');
        Route::get('/unassigned-waste-banks', [WasteBankController::class, 'unassignedWasteBank'])->name('waste-bank.unassigned');
        Route::resource('waste-bank', WasteBankController::class);

        // Manajemen Customer
        Route::get('/waste-bank-customer/data', [CustomerController::class, 'wasteBankCustomerData'])->name('waste-bank-customer.data');
        Route::resource('customer', CustomerController::class);
        Route::get('/customer-details-view', [CustomerController::class, 'viewCustomerDetails'])->name('customer-details.view');
        Route::get('/customer-by-waste-bank/data', [CustomerController::class, 'customerData'])->name('customer-by-waste-bank.data');

        // Manajemen Tonase
        Route::get('/waste-bank-waste-entri/data', [WasteEntriController::class, 'data'])->name('waste-entri.data');
        Route::get('waste-entri-details-view', [WasteEntriController::class, 'viewWasteEntriDetails'])->name('waste-entri-details.view');
        Route::get('waste-entri-data-by-waste-bank/data', [WasteEntriController::class, 'wasteEntriData'])->name('waste-entri-data-by-waste-bank.data');
        Route::resource('waste-entri', WasteEntriController::class);

        // Download Excel
        Route::get("/export-excel-by-month", [WasteEntriController::class, 'exportByMonth'])->name('export.data');
    });

    // Route Admin TPS3R
    Route::middleware(['checkRole:2'])->group(function () {
        // Manajemen Tonase By Admin TPS3R
        Route::get('/waste-entri-user/data', [WasteEntriController::class, 'dataTonaseByAdminTPS3R'])->name('waste-entri-user.data');
        Route::get('/waste-entri-user', [WasteEntriController::class, 'userIndexTonase'])->name('waste-entri-user.index');
        Route::post('/waste-entri-user', [WasteEntriController::class, 'userTPS3RStore'])->name('waste-entri-user.store');
        Route::get('/waste-entri-user/{id}', [WasteEntriController::class, 'userTPS3RShow'])->name('waste-entri-user.show');
        Route::put('/waste-entri-user/{id}', [WasteEntriController::class, 'userTPS3RUpdate'])->name('waste-entri-user.update');
        Route::delete('/waste-entri-user/{id}', [WasteEntriController::class, 'userTPS3RDestroy'])->name('waste-entri-user.destroy');

        // Manajemen Pelanggan By Admin TPS3R
        Route::get('/admin-tps3r-customer/data', [ManageCustomerByTPS3RController::class, 'data'])->name('admin-tps3r-customers.data');
        Route::resource('admin-tps3r-customer', ManageCustomerByTPS3RController::class);

        // Download Excel
        Route::get("/export-excel-tonase-by-tps3r", [WasteEntriController::class, 'exportTonaseByTPS3R'])->name('export-tonase-tps3r.data');
    });

    Route::middleware(['checkRole:3'])->group(function () {
        // Lihat Tonase
        Route::get('/waste-entri-facilitator', [WasteEntriController::class, 'dataTonaseByAdminFacilitator'])->name('waste-entri-facilitator.data');
        Route::get('/waste-entri-on-facilitator', [WasteEntriController::class, 'wasteEntriDataOnFacilitator'])->name('waste-entri-on-facilitator');
        Route::get('/tonase-data-facilitator', [WasteEntriController::class, 'viewTonaseFacilitator'])->name('view-tonase-facilitator');
        Route::get('/waste-entri-details-facilitator', [WasteEntriController::class, 'wasteEntriDetailsFacilitator'])->name('waste-entri-details-facilitator');

        // Download Excel
        Route::get("/export-excel-tonase-by-facilitator", [WasteEntriController::class, 'exportTonaseByFacilitator'])->name('export-tonase-facilitator.data');
    });
});
