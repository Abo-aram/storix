<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JwtHelper;

class HomeController extends Controller
{
    use JwtHelper;
    public function home(Request $request){
        $user = $this->getUser($request);
        $files = collect();

        if($user){
            $query = $user -> files();

            if($request->type === 'image'){
                $query ->whereIn('extension',['jpg','jpeg','png','gif']);

            }else if($request->type === 'other'){
                $query ->whereNotIn('extension',['jpg','jpeg','png','gif']);
            }
        }

        switch ($request->sort){
            case 'name_asc':
                $query -> orderBy('original_name','asc');
                break;
            case 'name_desc':
                $query -> orderBy('original_name','desc');
                break;
            case 'size_asc':
                $query -> orderBy('size','asc');
                break;
            case 'size_desc':
                $query -> orderBy('size','desc');
                break;
            case 'oddest':
                $query -> orderBy('created_at','asc');
                break;
            case 'newest':
                $query -> orderBy('created_at','desc');
                break;
            default:
        };

        if ($request->search) {
    $query->where('original_name', 'like', '%' . $request->search . '%');
}


        $files = $query->get();



            return view('components.home',compact('files'));}
        
        
}
