<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//user Routes
Route::get('/',[AuthController::class,'register'])->name('register');
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');
Route::get('/reset-password',[ResetController::class,'index'])->name('reset-password');
Route::get('/request-reset-password',[ResetController::class,'requestIndex'])->name('reset-password');



Route::post('/register',[AuthController::class,'registeruser'])->name('register.post');
Route::post('/login',[AuthController::class,'loginuser'])->name('login.post');
Route::post('/refresh',[AuthController::class,'refresh'])->name('refresh');
Route::post('/request-reset-password',[ResetController::class,'reset'])->name('request-reset-password.post');

Route::post('/reset-password',[ResetController::class,'resetpassword'])->name('reset-password.post');




//component Routes
Route::get('/home',[HomeController::class,'home'])->name('home');

