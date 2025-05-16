<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\JwtHelper;
use Symfony\Component\HttpFoundation\Response;


class RedirectIfAuthenticatedWithJwt
{
    use JwtHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('access_token');
        
        $payload = $this->validateJwt($token);
        $user = User::find($payload['id'] ?? null);
        $remember_token = $user->remember_token ?? null;

        

        if($payload && $payload['exp'] > time() || $remember_token && $remember_token['exp'] > time()) {
            return redirect()->route('home')->with('message', 'Already logged in.');
        }
        return $next($request);
    }
}
