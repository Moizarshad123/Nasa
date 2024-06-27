<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

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

    Route::controller(AdminController::class)->group(function() {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });
});
// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
