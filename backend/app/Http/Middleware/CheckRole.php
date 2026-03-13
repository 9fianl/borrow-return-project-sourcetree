<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if(!Auth::guard('api')->check()){
            return response()->json(['error'=>'กรุณา Login ก่อร'], 401);
        }

        if(Auth::guard('api')->user()->role !== $role){
            return response()->json(['error'=>'ไม่มีสิทธิ์เข้าถึง'],403);
        }

        return $next($request);
    }
}
