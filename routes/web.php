<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductdetailController;
use App\Http\Controllers\RegisterController;


Route::get('/auth/login',[LoginController::class, 'loginForm']);


Route::get('/',[HomeController::class, 'index']);

Route::get('/category/electronics',[CategoryController::class, 'detail']);

Route::get('/category/electronics/{slug}',[SubcategoryController::class, 'detail']);
Route::get('/category/electronics/tv/{slug}',[ProductdetailController::class, 'detail']);
