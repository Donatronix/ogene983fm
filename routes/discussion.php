<?php

use App\Http\Controllers\Discussion\DiscussionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'today-show'], function () {
        Route::get('/', [DiscussionController::class, 'dashboard'])->name('discussion.dashboard');
        Route::get('/create', [DiscussionController::class, 'create'])->name('discussion.create');
        Route::post('/store', [DiscussionController::class, 'store'])->name('discussion.store');
        Route::get('/{discussion}/edit', [DiscussionController::class, 'edit'])->name('discussion.edit');
        Route::put('/{discussion}/update', [DiscussionController::class, 'update'])->name('discussion.update');
        Route::delete('/{discussion}/delete', [DiscussionController::class, 'destroy'])->name('discussion.delete');
    });
});
Route::group(['prefix' => 'today-show'], function () {
    Route::get('/', [DiscussionController::class, 'index'])->name('discussion.index');
    Route::get('/{programme}', [DiscussionController::class, 'programme'])->name('discussion.programme');
    Route::get('/{programme}/{discussion}', [DiscussionController::class, 'show'])->name('discussion.show');
});
