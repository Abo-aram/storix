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
       
        $remember_token = $request->cookie('remember_token');
   


        
        

        


    
        if( $remember_token ) {
            
            $payload = $this->validateJwt($remember_token);
            if($payload == false) {
                return back()->with('message', 'invalid token');
            }
            $user = User::where('id', $payload['id'])->first();
            if($user && $user->remember_token == $remember_token) {
                return redirect()->route('home')->with('message', 'Already logged in.');
            }
            return back()->with('message', 'invalid token');
        }
        return $next($request);
    }
}
