<?php

use App\Http\Controllers\Category\CategoryController;
use Illuminate\Support\Facades\Route;

//categories
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'dashboard'])->name('category.dashboard');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/{category}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy'])->name('category.delete');
    });
});
