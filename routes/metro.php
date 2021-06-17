<?php

use App\Http\Controllers\Metro\MetroController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'metro'], function () {
        Route::get('/', [MetroController::class, 'dashboard'])->name('metro.dashboard');
        Route::get('/create', [MetroController::class, 'create'])->name('metro.create');
        Route::post('/store', [MetroController::class, 'store'])->name('metro.store');
        Route::get('/{metro}/edit', [MetroController::class, 'edit'])->name('metro.edit');
        Route::put('/{metro}/update', [MetroController::class, 'update'])->name('metro.update');
        Route::delete('/{metro}/delete', [MetroController::class, 'destroy'])->name('metro.delete');
    });
});
Route::group(['prefix' => 'metro'], function () {
    Route::get('/', [MetroController::class, 'index'])->name('metro.index');
    Route::get('/{category}', [MetroController::class, 'category'])->name('metro.category');
    Route::get('/{category}/{metro}', [MetroController::class, 'show'])->name('metro.show');
});
