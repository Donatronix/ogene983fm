<?php

namespace App\Providers;

use App\Models\Category\Category;
use App\Models\Post\Post;
use App\Models\Presenter\Presenter;
use App\Models\Programme\Programme;
use App\Models\Tag\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer(['layouts.pages.includes.navbar', 'site.pages.index'], function ($view) {
            $programmes = Programme::with(['description', 'image', 'programmeTimes'])->get();
            $view->with('programmes', $programmes);
        });

        view()->composer('layouts.pages.includes.navbar', function ($view) {
            $presenters = Presenter::with(['description', 'image'])->get();
            $view->with('presenters', $presenters);
        });

        view()->composer(['layouts.pages.includes.navbar', 'site.pages.index', 'site.pages.blog', 'site.pages.programmes.blog', 'site.pages.presenters.blog', 'site.pages.gallery.blog'], function ($view) {
            $categories = Category::with(['description', 'image'])->get();
            $view->with('categories', $categories);
        });

        view()->composer(['layouts.pages.includes.navbar', 'site.pages.index', 'site.pages.blog', 'site.pages.programmes.blog', 'site.pages.presenters.blog', 'site.pages.gallery.blog'], function ($view) {
            $tags = Tag::all();
            $view->with('tags', $tags);
        });

        view()->composer(['site.pages.blog', 'site.pages.programmes.blog', 'site.pages.presenters.blog', 'site.pages.gallery.blog'], function ($view) {
            $recents = Post::with(['category', 'category.description', 'category.image', 'description', 'image'])->orderBy('created_at', 'desc')->limit(5)->get();
            $view->with('recents', $recents);
        });
    }
}
