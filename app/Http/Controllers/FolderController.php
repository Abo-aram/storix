<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;


use Illuminate\Support\Facades\Storage;

use App\Models\Folder;
use App\JwtHelper;

class FolderController extends Controller
{
    use JwtHelper;
    public function createFolder(Request $request)
    {
        $user = $this->getUser($request);
        $request->validate([
            'stored_name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',


        ]);

        $parentPath = '';
        if($request->parent_id){
            $parent= Folder::findOrFail($request->parent_id);
            $parentPath = $parent->path;
        }

        $folder = Folder::create([
            'user_id' => $user->id,
            'name' => $request->stored_name,
            'path' => $parentPath . '/' . $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'message' => 'Folder created successfully',
        ], 201);
        
    }

    public function getFolders(Request $request){
        $user = $this->getUser($request);
        $folders = Folder::where('user_id', $user->id)->get();

        return response()->json($folders);
    }
}
