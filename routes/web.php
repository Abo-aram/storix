<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResetController;


//user routes
//register
Route::get('/',[AuthController::class,'register'])->name('register');
Route::post('/register',[AuthController::class,'registeruser'])->name('register.post');



//login
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'loginuser'])->name('login.post');


//logout
Route::get('/logout',[AuthController::class,'logout'])->name('logout');


//reset password
Route::get('/reset-password',[ResetController::class,'index'])->name('reset-password');
Route::post('/reset-password',[ResetController::class,'resetpassword'])->name('reset-password');

//request reset password
Route::get('/request-reset-password',[ResetController::class,'requestIndex'])->name('request-reset-password');
Route::post('/request-reset-password',[ResetController::class,'reset'])->name('request-reset-password.post');

//verify email
Route::get('/request-verify-email',[AuthController::class,'verifyEndex'])->name('request-verify-email');
Route::post('/request-verify-email',[AuthController::class,'verify'])->name('request-verify-email');
Route::get('/verified',[AuthController::class,'verified'])->name('verified');

//









Route::post('/refresh',[AuthController::class,'refresh'])->name('refresh');



//component Ro
Route::get('/home',[HomeController::class,'home'])->name('home');

