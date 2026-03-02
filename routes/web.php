<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductdetailController;
use App\Http\Controllers\RegisterController;


Route::get('/auth/login',[LoginController::class, 'loginForm'])->name('login.form');
Route::post('/account/login',[LoginController::class, 'loginUser'])->name('login.store');

Route::get('/auth/signup',[RegisterController::class, 'signupForm'])->name('signup.form');
Route::post('/account/signup',[RegisterController::class, 'signupUser'])->name('signup.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/',[HomeController::class, 'index']);

Route::get('/category/electronics',[CategoryController::class, 'detail']);

Route::get('/category/electronics/{slug}',[SubcategoryController::class, 'detail']);
Route::get('/category/electronics/tv/{slug}',[ProductdetailController::class, 'detail']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashbordController::class, 'index'])
        ->name('admin.dashboard');
});
