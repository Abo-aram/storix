<?php

use App\Http\Controllers\fileController;
use App\Http\Controllers\FolderController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\dashboard;
use App\Http\Middleware\JwtAuth;
use App\Http\Middleware\RedirectIfAuthenticatedWithJwt;


//user routes
//register

Route::post('/register',[AuthController::class,'registeruser'])->name('register.post');

Route::get('/alpine', function () {
    return view('partials.alpine');
})->name('alpine');

//login

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



   
Route::middleware([JwtAuth::class])->group(function () {
    Route::get('/home',[HomeController::class,'index'])->name('home');
    Route::post('/home/getFiles',[HomeController::class,'home'])->name('getFiles');
    Route::post('/upload',[fileController::class,'upload'])->name('upload');
    Route::get('/download/{id}/{isLink}', [fileController::class, 'downloadFile'])->name('download');


    Route::get('/dashboard/userName', [dashboard::class, 'userName'])->name('userName');
    Route::get('/dashboard/folders', [dashboard::class, 'folders'])->name('folders');
    Route::post('/delete', [fileController::class, 'deleteFile'])->name('delete');

    Route::post('/createfolder', [FolderController::class, 'createFolder'])->name('create.folder');
    Route::get('/getfolders', [FolderController::class, 'getFolders'])->name('folders.get');


});


Route::middleware([RedirectIfAuthenticatedWithJwt::class])->group(function () {
      Route::get('/login',[AuthController::class,'login'])->name('login');
      Route::get('/',[AuthController::class,'register'])->name('register');




});



   

//component Ro


