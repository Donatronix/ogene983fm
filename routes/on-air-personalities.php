<?php

use App\Http\Controllers\Presenter\PresenterController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'on-air-personalities'], function () {
        Route::get('/', [PresenterController::class, 'dashboard'])->name('presenter.dashboard');
        Route::get('/create', [PresenterController::class, 'create'])->name('presenter.create');
        Route::post('/store', [PresenterController::class, 'store'])->name('presenter.store');
        Route::get('/{presenter}/edit', [PresenterController::class, 'edit'])->name('presenter.edit');
        Route::put('/{presenter}/update', [PresenterController::class, 'update'])->name('presenter.update');
        Route::delete('/{presenter}/delete', [PresenterController::class, 'destroy'])->name('presenter.delete');
    });
});
Route::group(['prefix' => 'on-air-personalities'], function () {
    Route::get('/', [PresenterController::class, 'index'])->name('presenter.index');
    Route::get('/{presenter}', [PresenterController::class, 'show'])->name('presenter.show');
});
