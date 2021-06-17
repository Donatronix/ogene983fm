<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            //include routes for categories
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/categoriesRoute.php'));

            //include routes for usersRoutes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/usersRoutes.php'));

            //include routes for newsletterRoutes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/newsletterRoutes.php'));

            //include routes for contacts
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/contacts.php'));

            //include routes for blog
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/blog.php'));

            //include routes for gallery
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/gallery.php'));

            //include routes for programmes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/programmes.php'));

            //include routes for on-air-personalities
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/on-air-personalities.php'));

            //include routes for discussion
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/discussion.php'));

            //include routes for metro
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/metro.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
