<?php

namespace App\Http\Controllers\Security;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

 

class VerificationController extends Controller
{
    public function verify($user_id, Request $request){
        if (!$request->hasValidSignature()) {
            return response(['message'=>'invalid']);
        }
        $user = User::findOrFail($user_id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified(); 
        }
        return redirect()->to('/');
    } 
    public function resend(){
        if (auth()->user()->hasVerifiedEmail()) {
            return response(['message'=>'Email Already Verified']);
        }
        auth()->user()->sendEmailVerificationNotification();
        return response(['message'=>'Email Verification already send into your email']);
    }
}

