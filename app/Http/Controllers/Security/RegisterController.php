<?php 
namespace App\Http\Controllers\Security; 
use App\Http\Controllers\Security\ForgetPasswordController; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Password; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request; 
use Illuminate\Support\Str; 
use App\User; 
class RegisterController extends Controller
{ 
    // FOR USER REGISTRATION
    public function register(Request $request){ 
        // user_type
        // 1 -For Administration users(admin/user_type)
        // 2 -For Subscribers(email) then password will be set by the user
        // 3 -For web user(email/password)  

        // registration for ADMIN ////////////////////////////////////////////////
        if ($request->user_type == 1) { 
            $validateData = $request->validate([
                'name' => 'required|max:55',
                'email' =>'email|required|unique:users',
                'password' => 'required|confirmed',
                'user_type' => 'required'
            ]); 
            User::create($validateData)->sendEmailVerificationNotification(); 
            return response(['message'=>'Admin Created ! Reset password link sent on your email']); 
             
        }  
        // registration for Subcribers ////////////////////////////////////////////////
        if ($request->user_type == 2) { 
            $request->merge(['password' => Str::random()]); 
            $validateData = $request->validate([
                'name' => 'required|max:55',
                'email' =>'email|required|unique:users', 
                'user_type' => 'required',
                'password' => 'required'
            ]);   
            $credentials = $request->validate(['email'=>'required|email']); 
            User::create($validateData); 

            // send reset link
            Password::sendResetLink($credentials);  
            return response(['message'=>'reset password link sent on your email']); 
        } 

        // registration for customer  ////////////////////////////////////////////////
        if ($request->user_type == 3) { 
            $validateData = $request->validate([
                'name' => 'required|max:55',
                'email' =>'email|required|unique:users',
                'password' => 'required|confirmed',
                'user_type' => 'required'
            ]); 
            
            User::create($validateData)->sendEmailVerificationNotification(); 
            return response(['message'=>'customer User Created!']);
        } 
        
    }
}
