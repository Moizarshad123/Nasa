<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BigOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SmallOrderController;
use App\Http\Controllers\Admin\OrderNumberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TillController;

Route::match(['get', 'post'], 'login', [AdminController::class, 'login'])->name('login');
Route::match(['get', 'post'], 'register', [AdminController::class, 'register'])->name('register');
Route::get('logout', function (){
    auth()->logout();
    return redirect('/');
})->name('admin.logout');
Route::get('/', function (){
    return redirect('login');
});

Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    Route::resource('orderBigDC',BigOrderController::class);
    Route::resource('product',ProductController::class);
    Route::GET('products/order-big', [ProductController::class,"orderBigProducts"])->name('orderBigProducts');
    Route::GET('products/order-samll', [ProductController::class,"orderSmallProducts"])->name('orderSmallProducts');

    Route::resource('orderSmallDC',SmallOrderController::class);
    Route::resource('orderNumber',OrderNumberController::class);
    Route::resource('users',UserController::class);
    
    Route::controller(SmallOrderController::class)->group(function() {
        Route::GET('get-amall-order-rates', "getSmallOrderRate")->name('getSmallOrderRate');
        Route::get('print-small/{id}', 'printViewSmall')->name('print.view');
        Route::get('order-history', 'orderHistory')->name('orderHistory');
        Route::get('/assign-order-number-small', 'assignOrderNumberSmall')->name('assignOrderNumberSmall');
        Route::post('/assign-number-small', 'assignNumberSmall')->name('assignNumberSmall');

        Route::get('send-emails', 'sendEmails')->name('sendEmails');
        Route::get('mark-done/{id}', 'markDone')->name('markDone');

        

        
    });

    
    Route::controller(BigOrderController::class)->group(function() {
        Route::GET('get-sizes', "getSizes")->name('getSizes');
        Route::GET('sizes', "sizes")->name('sizes');
        Route::GET('get-size-amount', "getSizeAmount")->name('getSizeAmount');
        Route::GET('getStudioLPMTotal',"getStudioLPMTotal")->name('getStudioLPMTotal');
        Route::GET('getMediaLPMTotal',"getMediaLPMTotal")->name('getMediaLPMTotal');
        Route::GET('getStudioFrameTotal',"getStudioFrameTotal")->name('getStudioFrameTotal');
        Route::GET('editing-department',"editingDepartment")->name('editingDepartment');
        Route::GET('printing-department',"printingDepartment")->name('printingDepartment');
        Route::GET('all-orders',"allOrders")->name('allOrders');
        Route::POST('change-order-status',"changeStatus")->name('changeStatus');
        Route::GET('outstanding-amount',"outstandingAmount")->name('outstandingAmount');
        Route::GET('drop-job/{id}',"drop_job")->name('dropJob');
        Route::GET('sales-return/{id}',"sales_return")->name('salesReturn');
        Route::get('print/{id}', 'printView')->name('print.view');
        Route::get('/pos-slip', 'generatePdf');
        
        Route::get('/assign-order-number', 'assignOrderNumber')->name('assignOrderNumber');
        Route::post('/assign-number', 'assignNumber')->name('assignNumber');
        Route::GET('view-order/{id}',"viewOrder")->name('viewOrder');
        Route::GET('payment/{id}',"payment")->name('payment');
        Route::POST('add-payment',"add_payment")->name('addPayment');
        Route::GET('sales-return',"sales_return_report")->name('salesReturnReport');

        // Route::GET('change-order-status/{id}/{status}',"changeOrderStatus")->name('changeOrderStatus');
    });

    Route::controller(TillController::class)->group(function() {
        
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::match(['get', 'post'],'open-till', 'open_till')->name('openTill');
        Route::match(['get', 'post'],'close-till', 'close_till')->name('closeTill');
        Route::match(['get', 'post'],'cash-till', 'cash_till')->name('cashTill');
        Route::get('/till-close', 'tillCloseReceipt')->name('tillCloseReceipt');
        Route::get('/till-report', 'tillReport')->name('tillReport');
        Route::get('/till-report-receipt/{id}/{userid}', 'tillReportReceipt')->name('tillReportReceipt');

    });

    Route::controller(AdminController::class)->group(function() {
        
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::post('cash-in', 'cashIn')->name('cashIn');
        Route::match(['get', 'post'], '/settings', 'site_setting')->name('settings');
    });
});
