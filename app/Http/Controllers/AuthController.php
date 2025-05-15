<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\JwtHelper;

class AuthController extends Controller
{
    use  JwtHelper;

    //Register, stuff
    public function register(){
        if($this->AuthUser(request()->cookie('access_token')) != null){
            return redirect()->route('login')->with('message','token expired');
        }
        return view("auth.register");
    }
 
    public function registeruser(Request $request){
        //dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
            'remember_token' => Str::random(10),
            'refreshToken' => '',

        ]); 


       
        

         return redirect()->route('login')->with('message', 'Registration successful, please login');
    }
   


    
    public function login(){


        return view("auth.login");
    }

    public function loginuser(Request $request){


        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }

        if($user->email_verified_at == null){
            return redirect()->back()->with('message', 'Email not verified');
        }

        if( !Hash::check($request->password, $user->password)){
            return redirect()->back()->with('message', 'Invalid password');
        }

        $accessToken = $this->generateJwt([
            'id' => $user->id,
            'type' => 'access',
        ]);

        $refreshToken = $this->generateJwt([
            'id' => $user->id,
            'type'=> 'refresh',
        ], 60 * 24 * 15); // 15 days

        $user->refreshToken = $refreshToken;
        $user->save();

        $cookie = cookie(
        'access_token',           // name
        $accessToken,             // value
                       // sameSite
    );

       return redirect()->route('home')->withCookie($cookie)->with('message', 'Login successful');

    }

   


   



    public function refresh( $request){
        $refresh = $request->bearerToken();
        $payload = $this->validateJwt($refresh);


        if(!$payload || $payload['type'] != 'refresh'){
            return response()->json([
                'message' => 'Invalid refresh token'
            ], 401);
        }

        $user = User::find($payload['user_id']);
        if(!$user){
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }

        $newAccessToken = $this->generateJwt([
            'user_id' => $user->id,
            'type' => 'access',
        ]);

        return response()->json([
            'access_token' => $newAccessToken,
        ]);
    }

    public function logout(){
            return view("auth.logout");
        }


    public function verifyEndex(Request $request){
        $isVerified = false;
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }

        if ($user->email_verified_at != null) {
            $isVerified = true;
        }



        return view("auth.verify",compact($isVerified));
    }

    public function verify(Request $request){
        
    }


    public function verified(Request $request){
        
    }
}
