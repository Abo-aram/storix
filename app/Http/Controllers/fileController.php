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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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

    public function downloadFile(Request $request, $id , $isLink){
        
        $file = File::where('id', $id)->first();
        $user = $this->getUser($request);

        if(!$user){
            return redirect()->route('login')->with('message', 'Please login to download files.');
        }

       

        
        $filePath = $file->path;

      

        if(Storage::disk('public')->exists($filePath)){
            if($isLink == 'true' ){

                $signedUrl = URL::temporarySignedRoute(
                    'download',
                    now()->addMinutes(30),
                    ['id' => $id, 'isLink' => 'false']
                );



                return  $signedUrl;
            }

             if($user->id == $file->user_id){
                $fullPath = Storage::disk('public')->path($filePath);
                 return response()->download($fullPath); 
            }


            if($request->hasValidSignature()){
            $fullPath = Storage::disk('public')->path($filePath);
            return response()->download($fullPath); 
            }else{
                return redirect()->route('home')->with('message', 'Invalid download link or link has expired.');
            }

            
        }else{
            return response()->json(['message' => 'File not found in storage'], 404);
        }

    }

    public function deleteFile(Request $request, $id){
            $file = File::where('id', $id)->first();
            if(!$file){
                return response()->json(['message' => 'File not found'], 404);
            }
            $filePath = $file->path;


             if (Storage::disk('public')->exists($filePath)) {
                $file->delete();
            Storage::disk('public')->delete($filePath);
            return redirect()->back()->with('message', 'File deleted successfully.');
        }


    }

    
}
