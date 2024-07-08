<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BigOrderController;
use App\Http\Controllers\Admin\ProductController;



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

    
    Route::controller(BigOrderController::class)->group(function() {
        Route::GET('get-sizes', "getSizes")->name('getSizes');
        Route::GET('get-size-amount', "getSizeAmount")->name('getSizeAmount');
        Route::GET('getStudioLPMTotal',"getStudioLPMTotal")->name('getStudioLPMTotal');
        Route::GET('getMediaLPMTotal',"getMediaLPMTotal")->name('getMediaLPMTotal');
        Route::GET('getStudioFrameTotal',"getStudioFrameTotal")->name('getStudioFrameTotal');
        Route::GET('getMediaFrameTotal',"getMediaFrameTotal")->name('getMediaFrameTotal');

        
        
    });


    

    
    Route::controller(AdminController::class)->group(function() {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::match(['get', 'post'], '/settings', 'site_setting')->name('settings');
    });
});
// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
