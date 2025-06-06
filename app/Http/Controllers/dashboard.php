<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JwtHelper;

class dashboard extends Controller
{
     use  JwtHelper;
    public function userName(Request $request){
        $user = $this->getUser($request);
        if ($user){
            return $user->name;
        }



    }
}
