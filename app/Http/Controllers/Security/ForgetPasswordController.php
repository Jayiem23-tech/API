<?php

namespace App\Http\Controllers\Security;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Password; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 

class ForgetPasswordController extends Controller
{    
    public function forgot(Request $request){ 
        $credentials = $request->validate(['email'=>'required|email']);
        Password::sendResetLink($credentials); 
        return response(['message'=>'reset password link sent on your email']);
    } 


     public function reset(Request $request){

// ================== Reset Password in Website Platform ====================================

        if(Auth()->check()) {
            if (Hash::check($request->old_password, Auth()->user()->password)) {
                  $request->validate([
                    'old_password'     => 'required',
                    'password'     => 'required|confirmed'  
                ]);
                 
                $user = User::find(Auth()->user()->id);
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['message'=>'Password Change'], 200);
            }else{
                return response()->json(['Error'=>'Invalid Password'], 401); 
            }
        }  

// ================== Reset Password using Link ====================================

        $credentials = request()->validate([  
            'email'=>'required|email',
            'password' => 'required|string|max:25',
            'token' => 'required|string'
        ]); 
        $email_password_status = Password::reset($credentials, function($user,$password){
            $user->password = $password;
            $user->save();
            $users = User::findOrFail($user->id);
            if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified(); 
            }
        });  
        if ($email_password_status == Password::INVALID_TOKEN) {
                return response(['Error'=>'Invalid Reset Password Token']);
        } 
        return response()->json(['message'=>'Password Change'], 200);
    }
}
