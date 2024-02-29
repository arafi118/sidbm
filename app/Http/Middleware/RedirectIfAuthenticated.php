<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        if (auth()->guard('master')->check()) {
            if (auth()->guard('master')->user()->akses == 'master') {
                return redirect('/master/dashboard');
            }
        }

        if (auth()->guard('kab')->check()) {
            return redirect('/kab/dashboard');
        }

        if (auth()->guard('web')->check()) {
            return redirect('/dashboard');
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
