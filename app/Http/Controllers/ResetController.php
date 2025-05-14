<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\JwtHelper;
use Illuminate\Support\Facades\Mail;

class ResetController extends Controller
{
    use JwtHelper;
    public function index(Request $request){
        $token = $request->query('token');
        $email = $request->query('email');
        return view('Auth.reset',compact('token','email'));
    }

    public function requestIndex(){
        return view('Auth.requestPassword');
    }







    public function reset(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $token = mt_rand(100000, 999999);
        $user = User::where('email', $request->email)->first();
        $isSent = passwordResetToken::where('email', $request->email)->exists();
        $resetLink = url('/reset-password') . '?token=' . $token .
             '&email=' . urlencode($request->email);



        

        if($isSent){
            return response()->json([
                'message' => 'Token already sent to your email',
            ], 400);
        }

        if(!$user){
            return redirect()->back()->with('message', 'Email not found');
        }

      

        PasswordResetToken::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
            
        ]);

 

        Mail::send([], [], function ($message) use ($request, $resetLink) {
            $message->to($request->email)
                    ->subject('Password Reset Request')
                    ->html('
                        <p>Hello!</p>
                        <p>Click below to reset your password:</p>
                        <p>
                            <a href="' . $resetLink . '" style="padding: 10px 20px; background-color: #3490dc; color: white; text-decoration: none; border-radius: 5px;">
                                Reset Password
                            </a>
                        </p>
                        <p>If you didn’t request this, ignore the email.</p>
                    ');
        });




        
      
        return redirect()->route('reset-password')->with('message', 'Password reset link sent to your email');
       

        
    }

    public function resetpassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6',

        ]);


        $hashedToken = hash('sha256', $request->token);
        $record = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token);


        

        if(!$record){
            return response()->json([
                'message' => 'Invalid or expired token',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $record->delete();
        passwordResetToken::where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ], 200);

    }
}
