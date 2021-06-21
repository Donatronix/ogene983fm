<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Tag\TagController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
});
Route::get('/home', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');


Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tag.show');
Route::get('/search', [SearchController::class, 'show'])->name('search');


Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    if (function_exists('exec')) {
        exec('rm ' . storage_path('logs/*'));
    }
    return redirect()->route('home');
});

Route::get('/test', function () {
    echo str_replace("ogene983fm", "public_html", base_path());
});
