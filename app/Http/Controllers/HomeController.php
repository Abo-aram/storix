<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JwtHelper;

class HomeController extends Controller
{
    use JwtHelper;
    public function home(){
        if($this->AuthUser(request()->cookie('access_token')) == null){
             return  view('components.home');
        }

    }
}
