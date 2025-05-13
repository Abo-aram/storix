<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

//user Routes
Route::get('/',[AuthController::class,'register'])->name('register');
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');

Route::post('/register',[AuthController::class,'registeruser'])->name('register.post');
Route::post('/login',[AuthController::class,'loginuser'])->name('login.post');

Route::post('/refresh',[AuthController::class,'refresh'])->name('refresh');



//component Routes
Route::get('/home',[HomeController::class,'home'])->name('home');

