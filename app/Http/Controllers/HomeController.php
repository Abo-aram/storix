<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JwtHelper;

class HomeController extends Controller
{
    use JwtHelper;
    public function home(Request $request){
        $user = $this->getUser($request);
        $files = $user ? $user->files : collect();




            return view('components.home',compact('files'));}
        
        
}
