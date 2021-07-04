<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::get();
        if ($user) {
            $isAdmin = Auth::user()->isAdmin || Auth::user()->isSuperAdmin || Auth::user()->isPresenter;
            if ($isAdmin === false) //If user does //not have this permission
            {
                abort('401');
            }
        }
        return $next($request);
    }
}
