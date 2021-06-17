<?php

use App\Http\Controllers\Programme\ProgrammeController;
use App\Http\Controllers\Programme\ProgrammeTimeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'programmes'], function () {
        Route::get('/', [ProgrammeController::class, 'dashboard'])->name('programme.dashboard');
        Route::get('/create', [ProgrammeController::class, 'create'])->name('programme.create');
        Route::post('/store', [ProgrammeController::class, 'store'])->name('programme.store');
        Route::get('/{programme}/edit', [ProgrammeController::class, 'edit'])->name('programme.edit');
        Route::put('/{programme}/update', [ProgrammeController::class, 'update'])->name('programme.update');
        Route::delete('/{programme}/delete', [ProgrammeController::class, 'destroy'])->name('programme.delete');
        Route::get('/schedule/{programme}/create', [ProgrammeTimeController::class, 'create'])->name('programme.time.create');
        Route::post('/schedule/{programme}/store', [ProgrammeTimeController::class, 'store'])->name('programme.time.store');
        Route::get('/schedule/{programme}/{programmeTime}/edit', [ProgrammeTimeController::class, 'edit'])->name('programme.time.edit');
        Route::put('/schedule/{programme}/{programmeTime}/update', [ProgrammeTimeController::class, 'update'])->name('programme.time.update');
        Route::delete('/schedule/{programme}/{programmeTime}/delete', [ProgrammeTimeController::class, 'destroy'])->name('programme.time.delete');
    });
});
Route::group(['prefix' => 'programmes'], function () {
    Route::get('/', [ProgrammeController::class, 'index'])->name('programme.index');
    Route::get('/{programme}', [ProgrammeController::class, 'show'])->name('programme.show');
});

Route::get('/onAir', [ProgrammeController::class, 'onAir'])->name('onAir');
