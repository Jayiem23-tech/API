<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix'=>'users'],function(){

    Route::post('register','Security\RegisterController@register');
    Route::post('login','Security\AuthController@login');

    Route::post('password/forgot','Security\ForgetPasswordController@forgot');
    Route::post('password/reset','Security\ForgetPasswordController@reset')->name('password.reset'); 

    Route::get('email/verify/{id}','Security\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend','Security\VerificationController@resend')->name('verification.resend');
});

Route::group(['middleware'=>['admin_AccessControlMiddleware'],'prefix' => 'admin'],function(){
    Route::get('hello',function(){
        return 'hello';
    });  
});
 
   



