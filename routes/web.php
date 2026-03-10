<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductdetailController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\UserController;

Route::get('/auth/login',[LoginController::class, 'loginForm'])->name('login.form');
Route::post('/account/login',[LoginController::class, 'loginUser'])->name('login.store');

Route::get('/auth/signup',[RegisterController::class, 'signupForm'])->name('signup.form');
Route::post('/account/signup',[RegisterController::class, 'signupUser'])->name('signup.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index']);

Route::get('/product/{id}', [ProductdetailController::class, 'detail'])->name('product.show');

Route::get('/category/{id}', [CategoryController::class,'detail'])->name('category.products');

Route::get('/subcategory/{id}', [SubcategoryController::class,'detail'])->name('subcategory.products');

Route::get('/cart-list', [CartController::class,'list'])->name('cart.list');
Route::post('/add-to-cart/{id}', [CartController::class,'add'])->name('cart.add');
Route::post('/cart-update/{id}', [CartController::class,'update'])->name('cart.update');
Route::get('/remove-from-cart/{id}', [CartController::class,'remove'])->name('cart.remove');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashbordController::class, 'index'])
        ->name('admin.dashboard');

        
});


Route::get('/admin/users',[UserController::class, 'users'])->name('admin.user.users');
Route::get('/admin/add/user',[UserController::class, 'create'])->name('admin.user.adduser');
Route::post('/admin/users', [UserController::class, 'store'])->name('user.store');
     Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
     Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
     Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('/admin/products',[ProductController::class, 'products'])->name('admin.product.products');
Route::get('/admin/product',[ProductController::class, 'createproduct'])->name('product.create');
Route::post('/admin/store/product', [ProductController::class, 'storeproduct'])->name('product.store');
     Route::get('/product/{id}/edit', [ProductController::class, 'editproduct'])->name('product.edit');
     Route::get('/product/{id}/view', [ProductController::class, 'viewproduct'])->name('product.view');
     Route::put('/product/{id}', [ProductController::class, 'updateproduct'])->name('product.update');
     Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
     



Route::get('/admin/categories',[CategoryController::class, 'categories'])->name('admin.category.categories');
Route::get('/admin/category',[CategoryController::class, 'createcategory'])->name('category.create');
Route::post('/admin/store/category', [CategoryController::class, 'storecategory'])->name('category.store');
     Route::get('/category/{id}/edit', [CategoryController::class, 'editcategory'])->name('category.edit');
     Route::put('/category/{id}', [CategoryController::class, 'updatecategory'])->name('category.update');
     Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
      

Route::get('/admin/subcategories',[SubcategoryController::class, 'subcategories'])->name('admin.subcategory.subcategories');
Route::get('/admin/subcategory',[SubcategoryController::class, 'createsubcategory'])->name('subcategory.create');
Route::post('/admin/store/subcategory', [SubcategoryController::class, 'storesubcategory'])->name('subcategory.store');
     Route::get('/subcategory/{id}/edit', [SubcategoryController::class, 'editsubcategory'])->name('subcategory.edit');
     Route::put('/subcategory/{id}', [SubcategoryController::class, 'updatesubcategory'])->name('subcategory.update');
     Route::delete('/subcategory/{id}', [SubcategoryController::class, 'destroy'])->name('subcategory.destroy');
      


     

