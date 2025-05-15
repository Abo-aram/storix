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
            return redirect()->back()->with('message', 'Password reset link already sent to your email');
        }

        if(!$user){
            return redirect()->back()->with('message', 'Email not found');
        }

      

        PasswordResetToken::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
            
        ]);

 

        Mail::send([], [], function ($message) use ($request, $token,$resetLink) {
            $message->to($request->email)
                    ->subject('Reset your Storix password')
                    ->html('
                          <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                                    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                                        <h2 style="color: #2c3e50;">Hello,</h2>
                                        <p style="color: #555; font-size: 16px; line-height: 1.5;">
                                            You recently requested to reset your password. Use the following code to proceed:
                                        </p>
                                        
                                        <div style="background-color: #f0f4ff; padding: 20px; text-align: center; border-radius: 6px; margin: 20px 0;">
                                            <span style="font-size: 24px; font-weight: bold; color: #1d4ed8;">' . $token . '</span>
                                        </div>

                                         <p style="color: #555; font-size: 16px; line-height: 1.5;">for reseting password on device click the blow button  </p>
                                        <a href="' . $resetLink . '" style="display: inline-block; background-color: #1d4ed8; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;">Reset Password</a>

                                        <p style="color: #555; font-size: 14px;">
                                            If you didn’t request this, you can safely ignore this email.
                                        </p>

                                        <p style="color: #999; font-size: 12px; margin-top: 30px; text-align: center;">
                                            &copy; ' . date('Y') . ' YourAppName. All rights reserved.
                                        </p>

                                       
                                    </div>
                                </div>
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
            return redirect()->back()->with('message', 'Invalid token');
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return redirect()->back()->with('message', 'Email not found');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $record->delete();
        passwordResetToken::where('email', $request->email)->delete();

        return redirect()->route('login')->with('message', 'Password reset successfully');

    }
}
