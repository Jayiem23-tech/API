<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Support\Facades\Auth;


class AdminAcesscontrolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // user_type ==> [
        //     1 ADMIN USER,
        //     2 Subcriber,
        //     3 Customer
        // ]
         if (Auth::check() && Auth()->user()->user_type == '1') {
            return $next($request);
         }else{
            return response()->json(['message'=>'UnAuthorize Admin User']);
         }
        
    }
}
