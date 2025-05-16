<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\JwtHelper;

class fileController extends Controller
{
    use JwtHelper;

    
    public function upload(Request $request){
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $user = $this->getUser($request);
        $uploadedFile = $request->file('file');
        $stored_name = uniqid() . '.' . $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();
        $path = $uploadedFile->storeAs('uploads', $stored_name, 'public');


         File::create([
            'user_id' => $user->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $stored_name,
            'path' => $path,
            'size' => $size,
            'extension' => $extension,

        ]);

        return redirect()->back()->with('message', 'File uploaded successfully.');


        


    }


    
}
