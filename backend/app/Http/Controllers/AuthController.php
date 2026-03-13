<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users|string|max:255',
            'password'=>'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json([
            'message'=>'User successfully registered',
            'user'=> $user,
        ], 201);

    
    }

    public function login(Request $request)
{
    try {
        $credentials = $request->only('email', 'password');

        // 🚩 คราวนี้ auth('api')->attempt() จะทำงานได้แล้ว (ไม่ขึ้น undefined)
        // ลบบรรทัด JWTAuth::attempt(...) ออก แล้วใส่ตัวนี้แทน
if (!$token = auth()->guard('api')->attempt($credentials)) {
    return response()->json(['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
}

        $user = auth('api')->user();

        return response()->json([
            'token'   => $token, 
            'id'      => $user->id,
            'role'    => $user->role,
            'name'    => $user->name,
            'message' => 'Login Success'
        ], 200);

    } catch (\Exception $exception) {
        \Illuminate\Support\Facades\Log::error($exception);
        return response()->json(['error' => 'Server Error: ' . $exception->getMessage()], 500);
    }
}

    public function logout(){
        Auth::guard('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
}
