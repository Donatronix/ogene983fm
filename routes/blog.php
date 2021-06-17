<?php

use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'news-and-event'], function () {
        Route::get('/', [PostController::class, 'dashboard'])->name('post.dashboard');
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/store', [PostController::class, 'store'])->name('post.store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/{post}/update', [PostController::class, 'update'])->name('post.update');
        Route::delete('/{post}/delete', [PostController::class, 'destroy'])->name('post.delete');
    });
});
Route::group(['prefix' => 'news-and-event'], function () {
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/{category}', [PostController::class, 'category'])->name('post.category');
    Route::get('/{category}/{post}', [PostController::class, 'show'])->name('post.show');
});
