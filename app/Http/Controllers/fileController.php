<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use App\JwtHelper;

class fileController extends Controller
{
    use JwtHelper;

    
    public function upload(Request $request){
        $request->validate([
            'file' => 'required|file',
            'folder_id' => 'nullable',
            'stored_name' => 'nullable|string|max:255',
        ]);
        



        $user = $this->getUser($request);
        $uploadedFile = $request->file('file');
        $stored_name = $request->stored_name ?? time() . '_' . $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();

        $folderPath = '';
        if($request->folder_id){
            $folder = Folder::findOrFail($request->folder_id);
            $folderPath = trim($folder->path, '/'). '/';
        }







        $path = $uploadedFile->storeAs('uploads'. $folderPath , $stored_name, 'public');


         File::create([
            'user_id' => $user->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $stored_name,
            'path' => $path,
            'size' => $size,
            'extension' => $extension,
            'folder_id' => $request->folder_id,

        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
        ], 201);


        


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



                $file->updated_at = now();
                $file->save();
                return  $signedUrl;
            }

             if($user->id == $file->user_id){
                $fullPath = Storage::disk('public')->path($filePath);
                
                $file->updated_at = now();
                $file->save();
                 return response()->download($fullPath); 
            }


            if($request->hasValidSignature()){
            $fullPath = Storage::disk('public')->path($filePath);
                $file->updated_at = now();
                $file->save();
            return response()->download($fullPath); 
            }else{
                return redirect()->route('home')->with('message', 'Invalid download link or link has expired.');
            }

            
        }else{
            return response()->json(['message' => 'File not found in storage'], 404);
        }

    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        

        $user = $this->getUser($request);
        $file = File::where('id', $request->id)->first();

        // Check file existence and ownership
        if (!$file || $file->user_id !== $user->id) {
            return response()->json(['message' => 'File not found or unauthorized'], 404);
        }

        $filePath = $file->path;

        // Delete file from storage if it exists
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Always delete the DB record regardless of file existence
        $file->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);
    }


    
}
