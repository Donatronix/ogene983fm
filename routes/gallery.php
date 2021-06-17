<?php

use App\Http\Controllers\Gallery\AlbumController;
use App\Http\Controllers\Gallery\AlbumUploadController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/', [AlbumController::class, 'dashboard'])->name('gallery.album.dashboard');
        Route::get('/album/create', [AlbumController::class, 'create'])->name('gallery.album.create');
        Route::post('/album/store', [AlbumController::class, 'store'])->name('gallery.album.store');
        Route::get('/album/{album}/edit', [AlbumController::class, 'edit'])->name('gallery.album.edit');
        Route::put('/album/{album}/update', [AlbumController::class, 'update'])->name('gallery.album.update');
        Route::delete('/album/{album}/delete', [AlbumController::class, 'destroy'])->name('gallery.album.delete');
        Route::get('/album/{album}', [AlbumController::class, 'showAlbum'])->name('gallery.album.showAlbum');

        Route::get('/{album}', [AlbumUploadController::class, 'dashboard'])->name('gallery.album.upload.dashboard');
        Route::get('/album/{album}/uploads/create', [AlbumUploadController::class, 'create'])->name('gallery.album.upload.create');
        Route::post('/album/{album}/uploads/store', [AlbumUploadController::class, 'store'])->name('gallery.album.upload.store');
        Route::get('/album/{album}/uploads/{albumUpload}/edit', [AlbumUploadController::class, 'edit'])->name('gallery.album.upload.edit');
        Route::put('/album/{album}/uploads/{albumUpload}/update', [AlbumUploadController::class, 'update'])->name('gallery.album.upload.update');
        Route::delete('/album/uploads/{albumUpload}/delete', [AlbumUploadController::class, 'destroy'])->name('gallery.album.upload.delete');
    });
});
Route::group(['prefix' => 'gallery'], function () {
    Route::get('/', [AlbumController::class, 'index'])->name('gallery.album.index');
    Route::get('/album/{album}', [AlbumController::class, 'show'])->name('gallery.album.show');
    Route::get('/album/{album}/{albumUpload}', [AlbumUploadController::class, 'show'])->name('gallery.album.upload.show');
});
