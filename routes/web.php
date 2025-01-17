<?php

use App\Http\Controllers\AdminFacilitatorBillingController;
use App\Http\Controllers\AdminYrpwBillingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerByAdminFasilitator;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GarbageCollectionFeeController;
use App\Http\Controllers\ManageCustomerByTPS3RController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\WasteBankController;
use App\Http\Controllers\WasteEntriController;
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
Route::view('/customer_payment_records', 'admin-tps3r-new.manage-garbage-collection-fee.customer-payment-record-pdf');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard-new', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Route Admin YRPW
    Route::middleware(['checkRole:1'])->group(function () {
        // Rata" Tonase perhari 
        Route::get('tonase-average-by-current-date', [DashboardController::class, 'getAverageTonaseByCurrentDate'])->name('average-tonase-by-current-date');
        // mendapatkan rata" tonase dari awal bulan sampai tanggal saat ini
        Route::get('tonase-average', [DashboardController::class, 'getAverageTonase'])->name('average-tonase');

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
        Route::post('/upload-file-customer-yrpw', [CustomerController::class, 'uploadFileCustomer'])->name('file-customer-yrpw');
        Route::get('/progress', [CustomerController::class, 'progress'])->name('progress-view-yrpw');
        Route::get('/batch-status', [CustomerController::class, 'batchStatus'])->name('batch-status-yrpw');

        // Manajemen Tonase
        Route::get('/waste-bank-waste-entri/data', [WasteEntriController::class, 'data'])->name('waste-entri.data');
        Route::get('waste-entri-details-view', [WasteEntriController::class, 'viewWasteEntriDetails'])->name('waste-entri-details.view');
        Route::get('waste-entri-data-by-waste-bank/data', [WasteEntriController::class, 'wasteEntriData'])->name('waste-entri-data-by-waste-bank.data');
        Route::resource('waste-entri', WasteEntriController::class);

        // Data Iuran
        Route::get('/bill-report-customer', [AdminYrpwBillingController::class, 'data'])->name('customer-billing');
        Route::get('/bill-report-customer-view', [AdminYrpwBillingController::class, 'index'])->name('customer-billing-view');
        Route::get('/bill-report-customer-details-view', [AdminYrpwBillingController::class, 'billCustomerDetail'])->name('billing-customer-details');
        Route::get('/bill-customer-paid', [AdminYrpwBillingController::class, 'checkMonthlyBillPaid'])->name('monthlyBillPaidYrpw');
        Route::get('/bill-customer-unpaid', [AdminYrpwBillingController::class, 'checkMonthlyBillUnpaid'])->name('monthlyBillUnpaidYrpw');


        // Download Excel
        Route::get("/export-excel-tonase-by-yrpw", [WasteEntriController::class, 'exportTonaseByYRPW'])->name('export-tonase-yrpw.data');
        Route::get('/export-excel-customer-unpaid-monthly-bill-yrpw', [AdminYrpwBillingController::class, 'exportCustomerUnpaidMonthlyBill'])->name('export-customer-unpaid-yrpw');
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
        Route::post('/upload-file-customer-tps3r', [ManageCustomerByTPS3RController::class, 'uploadFileCustomer'])->name('file-customer-tps3r');
        Route::get('/progress-tps3r', [ManageCustomerByTPS3RController::class, 'progress'])->name('progress-view-tps3r');
        Route::get('/batch-status-tps3r', [ManageCustomerByTPS3RController::class, 'batchStatus'])->name('batch-status-tps3r');


        // Manajemen Iuran
        Route::get('/monthly-bill-data', [GarbageCollectionFeeController::class, 'customerData'])->name('monthly-bill.data');
        Route::get('/monthly-bill-view', [GarbageCollectionFeeController::class, 'monthlyBill'])->name('monthly-bill.view');
        Route::get('/monthly-bill-paid-view', [GarbageCollectionFeeController::class, 'checkMonthlyBillPaidView'])->name('viewMonthlyBillPaid');
        Route::get('/monthly-bill-unpaid-view', [GarbageCollectionFeeController::class, 'checkMonthlyBillUnpaidView'])->name('viewMonthlyBillUnpaid');
        Route::get('/monthly-bill-paid', [GarbageCollectionFeeController::class, 'checkMonthlyBillPaid'])->name('monthlyBillPaid');
        Route::get('/monthly-bill-unpaid', [GarbageCollectionFeeController::class, 'checkMonthlyBillUnpaid'])->name('monthlyBillUnpaid');
        Route::post('/monthly-bill', [GarbageCollectionFeeController::class, 'store'])->name('store-payment');
        Route::delete('/monthly-bill-paid/{id}', [GarbageCollectionFeeController::class, 'deletedWastePayment'])->name('deletePayment');

        // Download pdf detail pembayaran customer
        Route::get('/download-pdf-detail-paid-customer', [GarbageCollectionFeeController::class, 'downloadDetailPaidCustomerByTPS3R'])->name('download-customer-paid-tps3r');
        Route::get('/download-pdf-view', [GarbageCollectionFeeController::class, 'downloadPdf'])->name('download-pdf-url');

        // Download Excel
        Route::get("/export-excel-tonase-by-tps3r", [WasteEntriController::class, 'exportTonaseByTPS3R'])->name('export-tonase-tps3r.data');
        // Download Excel Customer
        Route::get("/export-excel-customer-by-tps3r", [ManageCustomerByTPS3RController::class, 'exportCustomer'])->name('export-customer-tps3r');
        Route::get('/export-excel-customer-unpaid-monthly-bill-tps3r', [GarbageCollectionFeeController::class, 'exportCustomerUnpaidMonthlyBill'])->name('export-customer-unpaid');
    });

    Route::middleware(['checkRole:3'])->group(function () {
        // Lihat Tonase
        Route::get('/waste-bank-view-facilitator', [WasteEntriController::class, 'viewWasteBankFacilitator'])->name('view-waste-bank-facilitator.view');
        Route::get('/waste-entri-details-facilitator', [WasteEntriController::class, 'wasteEntriDetailsFacilitator'])->name('waste-entri-details-facilitator.view');
        Route::get('/waste-bank-facilitator', [WasteEntriController::class, 'wasteBankFacilitator'])->name('waste-bank-facilitator.data');
        Route::get('/waste-entri-on-facilitator', [WasteEntriController::class, 'wasteEntriDataOnFacilitator'])->name('waste-entri-facilitator.data');

        // Lihat Data Pelanggan
        Route::get('/waste-bank-customer-admin-facilitator/data', [CustomerByAdminFasilitator::class, 'wasteBankCustomerData'])->name('waste-bank-customer-onfacilitator.data');
        Route::get('/customer-details-view-facilitator', [CustomerByAdminFasilitator::class, 'viewCustomerDetailsFacilitator'])->name('customer-details-facilitator.view');
        Route::get('/customer-by-waste-bank-facilitator/data', [CustomerByAdminFasilitator::class, 'customerDataFacilitator'])->name('customer-by-waste-bank-facilitator.data');
        Route::resource('customer-by-admin-facilitator', CustomerByAdminFasilitator::class);

        // Data Iuran
        Route::get('/bill-report-customer-facilitator', [AdminFacilitatorBillingController::class, 'data'])->name('customer-billing-facilitator');
        Route::get('/bill-report-customer-view-facilitator', [AdminFacilitatorBillingController::class, 'index'])->name('customer-billing-view-facilitator');
        Route::get('/bill-report-customer-details-view-facilitator', [AdminFacilitatorBillingController::class, 'billCustomerDetail'])->name('billing-customer-details-facilitator');
        Route::get('/bill-customer-paid-facilitator', [AdminFacilitatorBillingController::class, 'checkMonthlyBillPaid'])->name('monthlyBillPaidFacilitator');
        Route::get('/bill-customer-unpaid-facilitator', [AdminFacilitatorBillingController::class, 'checkMonthlyBillUnpaid'])->name('monthlyBillUnpaidFacilitator');
        Route::get('/export-excel-customer-unpaid-monthly-bill-facilitator', [AdminFacilitatorBillingController::class, 'exportCustomerUnpaidMonthlyBill'])->name('export-customer-unpaid-facilitator');

        // Download Excel
        // Data Tonase
        Route::get("/export-excel-tonase-by-facilitator", [WasteEntriController::class, 'exportTonaseByFacilitator'])->name('export-tonase-facilitator.data');
        // Data Pelanggan
        Route::get("/export-excel-customer-by-facilitator", [CustomerByAdminFasilitator::class, 'exportCustomerByFacilitator'])->name('customer-export-facilitator');
    });
});

Route::fallback(function () {
    abort(404);
});
