<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JwtHelper;

class HomeController extends Controller
{
    use JwtHelper;
    public function home(){
        
            return view('components.home');}
        
        
}
