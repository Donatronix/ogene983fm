<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
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
            if (Auth::user()->isSuperAdmin === false) //If user does //not have this permission
            {
                abort('401');
            }
        }
        return $next($request);
    }
}
