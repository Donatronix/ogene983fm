<?php

use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserAboutController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\User\UserAvatarController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserPermissionController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserRoleController;
use App\Http\Controllers\User\UserSocialMediaHandleController;
use Illuminate\Support\Facades\Route;

//categories
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {

    Route::group(['middleware' => ['superAdmin']], function () {
        Route::get('members', [UserController::class, 'index'])->name('users.index');
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });

    Route::group(['prefix' => 'member'], function () {
        Route::group(['middleware' => ['superAdmin']], function () {
            Route::post('/roles/{user}/edit', [UserRoleController::class, 'edit'])->name('roles.edit.user');
            Route::post('/roles/{user}/save', [UserRoleController::class, 'store'])->name('roles.store.user');
            Route::post('/roles/{user}/update', [UserRoleController::class, 'update'])->name('roles.update.user');

            Route::post('/permissions/{user}/edit', [UserPermissionController::class, 'edit'])->name('permissions.edit.user');
            Route::post('/permissions/{user}/save', [UserPermissionController::class, 'store'])->name('permissions.store.user');
            Route::post('/permissions/{user}/update', [UserPermissionController::class, 'update'])->name('permissions.update.user');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/{user}/address', [UserAddressController::class, 'create'])->name('user.address.create');
            Route::post('/{user}/address', [UserAddressController::class, 'store'])->name('user.address.store');
            Route::get('/{user}/address/{address}/edit', [UserAddressController::class, 'edit'])->name('user.address.edit');
            Route::put('/{user}/address/{address}/update', [UserAddressController::class, 'update'])->name('user.address.update');
            Route::delete('/{user}/address/{address}/delete', [UserAddressController::class, 'destroy'])->name('user.address.delete');

            Route::get('/{user}-image/edit', [UserAvatarController::class, 'edit'])->name('user.avatar.edit');
            Route::post('/{user}-image/update', [UserAvatarController::class, 'update'])->name('user.avatar.update');
            Route::get('/{user}', [UserProfileController::class, 'getProfile'])->name('user.myProfile');
            Route::delete('/{user}/delete', [UserController::class, 'destroy'])->name('user.delete');
            Route::get('/{user}/verify', [UserController::class, 'verifyUser'])->name('user.verify');

            Route::get('/{user}/social-media-handle', [UserSocialMediaHandleController::class, 'create'])->name('user.socialmediahandle.create');
            Route::post('/{user}/social-media-handle', [UserSocialMediaHandleController::class, 'store'])->name('user.socialmediahandle.store');
            Route::get('/{user}/social-media-handle/{socialmediahandle}/edit', [UserSocialMediaHandleController::class, 'edit'])->name('user.socialmediahandle.edit');
            Route::put('/{user}/social-media-handle/{socialmediahandle}/update', [UserSocialMediaHandleController::class, 'update'])->name('user.socialmediahandle.update');
            Route::delete('/{user}/social-media-handle/{socialmediahandle}/delete', [UserSocialMediaHandleController::class, 'destroy'])->name('user.socialmediahandle.delete');

            Route::get('/{user}/about', [UserAboutController::class, 'create'])->name('user.about.create');
            Route::post('/{user}/about', [UserAboutController::class, 'store'])->name('user.about.store');
            Route::get('/{user}/about/edit', [UserAboutController::class, 'edit'])->name('user.about.edit');
            Route::put('/{user}/about/update', [UserAboutController::class, 'update'])->name('user.about.update');
            Route::delete('/{user}/about/delete', [UserAboutController::class, 'destroy'])->name('user.about.delete');
            Route::post('/{user}/{role}', [UserController::class, 'upgradeMembership'])->name('user.upgrade.membership');
        });
    });
});


Route::group(['prefix' => 'member', 'middleware' => ['auth']], function () {
    Route::post('/{user}/profile-dialog', [UserProfileController::class, 'profileDialog'])->name('user.profile.dialog');
    Route::get('/{user}/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::get('/profile/{user}/change-password', [UserProfileController::class, 'changePassword'])->name('user.profile.changePassword');
    Route::post('/profile/{user}/change-password', [UserProfileController::class, 'updatePassword'])->name('user.profile.updatePassword');
    Route::get('/profile/{user}/{upgrade}', [UserProfileController::class, 'upgrade'])->name('user.profile.upgrade');
});
