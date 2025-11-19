<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(auth()->check() , auth()->user()->is_organization);
        if(auth()->check() && auth()->user()->is_organization){
            return $next($request);

        }
        return redirect('/organization')->with(['message'=>'Not allowed to access!' , 'alert-type'=>'error']);
    }
}
