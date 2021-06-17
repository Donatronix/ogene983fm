<?php

use App\Http\Controllers\SongOfTheWeek\SongOfTheWeekController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'song-of-the-week'], function () {
        Route::get('/', [SongOfTheWeekController::class, 'dashboard'])->name('songoftheweek.dashboard');
        Route::get('/create', [SongOfTheWeekController::class, 'create'])->name('songoftheweek.create');
        Route::post('/store', [SongOfTheWeekController::class, 'store'])->name('songoftheweek.store');
        Route::get('/{songOfTheWeek}/edit', [SongOfTheWeekController::class, 'edit'])->name('songoftheweek.edit');
        Route::put('/{songOfTheWeek}/update', [SongOfTheWeekController::class, 'update'])->name('songoftheweek.update');
        Route::delete('/{songOfTheWeek}/delete', [SongOfTheWeekController::class, 'destroy'])->name('songoftheweek.delete');
    });
});
Route::group(['prefix' => 'song-of-the-week'], function () {
    Route::get('/{songOfTheWeek}', [SongOfTheWeekController::class, 'show'])->name('songoftheweek.show');
});
