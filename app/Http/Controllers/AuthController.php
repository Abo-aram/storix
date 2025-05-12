<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    //Register, stuff
    public function register(){
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

        ]); 


       
        

         return redirect()->route('login')->with('message', 'Registration successful, please login');
 }

    
    public function login(){
        return view("auth.login");
    }

    public function loginuser(Request $request){

        $request->validate([
            'email  ' => 'required|email',
            'password' => 'required',

        ]);

        
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return redirect()->back()->with('message', 'Email not found');
        }

        if(!Hash::check($request->password, $user->password)){
            return redirect()->back()->with('message', 'Password is incorrect');
        }

        
         return redirect()->back()->with('message', 'Registration successful, please login');

    }

    public function logout(){
        return view("auth.logout");
    }
}
