<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has any of the restricted roles
            if ($user->hasAnyRole($roles)) {
                Auth::logout();
                return abort(403,'Your role is restricted from logging in.  المستخدم غير مصرح له بالدخول');
            }
        }
        return $next($request);
    }
}
