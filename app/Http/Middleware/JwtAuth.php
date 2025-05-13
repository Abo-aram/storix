<?php

namespace App\Http\Middleware;

use Closure;
use App\JwtHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    use JwtHelper;


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
   
        
    


        return $next($request);
    }
}
