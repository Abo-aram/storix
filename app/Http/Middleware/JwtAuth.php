<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\JwtHelper;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    use JwtHelper;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->cookie('access_token');

        // If no access token, redirect
        if (!$accessToken) {
            return redirect()->route('login')->with('message', 'Access token missing.');
        }

        $accessPayload = $this->validateJwt($accessToken);

        // If token is valid and not expired, proceed
        if ($accessPayload && $accessPayload['exp'] > time()) {
            return $next($request);
        }

        // If access token is expired, try refresh
        if ($accessPayload && $accessPayload['exp'] < time()) {
            $user = User::find($accessPayload['id']);

            if (!$user || !$user->refreshToken) {
                return redirect()->route('login')->with('message', 'Session expired.');
            }

            $refreshPayload = $this->validateJwt($user->refreshToken);

            if (!$refreshPayload || $refreshPayload['exp'] < time()) {
                return redirect()->route('login')->with('message', 'Refresh token expired.');
            }

            // Refresh access token
            $newAccessToken = $this->generateJwt([
                'id' => $user->id,
                'type' => 'access',
            ], 60 * 15); // 15 minutes

            $cookie = cookie(
                'access_token',
                $newAccessToken,   
                60*24*15               // SameSite
            );

            $response = $next($request);
            $response->headers->setCookie($cookie);
            return $response;
        }

        
        return $next($request);
    }
}
