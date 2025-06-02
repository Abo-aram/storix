<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\JwtHelper;

class AuthController extends Controller
{
    use  JwtHelper;

    //Register, stuff
    public function register(){

        // if(  $this->AuthUser(request()->cookie('access_token')) == true){
        //     return redirect()->route('home');

        // }else{
          
        // }
        
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


        // if(  $this->AuthUser(request()->cookie('access_token')) == true){
        //     return redirect()->route('home');

        // }else{
         
        // }
          return view("auth.login"); 
    }

    public function loginuser(Request $request){


        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);



        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }

       
        if( !Hash::check($request->password, $user->password)){
            return redirect()->back()->with('message', 'Invalid password');
        }

         if($user->email_verified_at == null){
            return redirect()->route('request-verify-email',['id' => $user->id]);
        }

        $remember_me = null;

        if ($request->remember == true) {
            $remember_Token = $this->generateJwt([
                'id' => $user->id,
                'type' => 'remember',
            ], 60 * 24 * 15); // 15 days
        }

        

        $accessToken = $this->generateJwt([
                'id' => $user->id,
                'type' => 'access',
                'exp' => time() + 60 * 15, // 15 minutes
        ],15 ); // 15 minutes
        

        $refreshToken = $this->generateJwt([
            'id' => $user->id,
            'type'=> 'refresh',
        ], 60 * 24 * 15); // 15 days

        $user->refreshToken = $refreshToken;
        $user->remember_token = $remember_Token ?? null;
        $user->save();

     
    Cookie::queue('access_token', $accessToken,  60* 15); // 15 minutes
    Cookie::queue('remember_token', $remember_Token, 60 * 24 * 15); // 15 days

       return redirect()->route('home')->with('message', 'Login successful');

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
        
        $user = User::where('id', $request->id)->first();

        $resetLink = url('http://localhost:8000/verified') . '?email=' . urlencode($user->email);



        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }

        if ($user->email_verified_at != null) {
            $isVerified = true;
        }
        else{
            Mail::send([], [], function ($message) use ($user, $resetLink) {
                    $message->to($user->email)
                        ->subject('Reset your Storix password')
                        ->html('
                            <h1>Verify your email</h1>
                            <p>Click the link below to verify your email:</p>
                            <a href="' . $resetLink . '">Verify Email</a>
                            <p>This link will expire in 60 minutes.</p>', 'text/html');
            });
        }







        return view('auth.verify', compact('isVerified'));
    }

    public function verify(Request $request){
        
    }


    public function verified(Request $request){
        $user = User::where('email', $request->query('email'))->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 401);    
        }

        $user->email_verified_at = now();
        $user->save();

        return view('auth.verified')->with('message', 'Email verified successfully');
        
    }
}
