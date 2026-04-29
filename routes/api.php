<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Api\UserController;
use APP\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubcategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/stripe/webhook', [CheckoutController::class, 'stripeWebhook']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {



    // User Routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/store', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);


   // Profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/change-password', [UserController::class, 'updatePassword']);
    Route::get('/orders', [UserController::class, 'orderHistory']);

    // Products
    // Route::get('/products', [ProductController::class, 'index']);
    // Route::post('/product/store', [ProductController::class, 'store']);
    // Route::get('/product/{id}', [ProductController::class, 'show']);
    // Route::put('/product/{id}', [ProductController::class, 'update']);
    // Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'categorystore']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Subcategories
    Route::get('/subcategory', [SubcategoryController::class, 'index']);
    Route::post('/subcategories', [SubcategoryController::class, 'subcategorystore']);
    Route::get('/subcategories/{id}', [SubcategoryController::class, 'show']);
    Route::put('/subcategories/{id}', [SubcategoryController::class, 'update']);
    Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy']);
});