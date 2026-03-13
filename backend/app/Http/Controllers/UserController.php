<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function update(Request $request,$id){
        
        $user = User::findOrFail($id);

        $payload = [
            'name'=>$request->name,
            'email'=>$request->email,
        ];

        if($request->filled('password')){
            $payload['password'] = bcrypt($request->password);
        }

        $user->update($payload);

        return response()->json([
            'message'=>'User successfully updated',
            'data'=>$user
        ]);
    }

    public function destroy(Request $request ,$id){

        if($request->user()->id == $id){
            return response()->json([
                'message'=>'คุณไม่สามารถลบตัวคุณเองได้'
            ], 403);
        }

        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message'=>'User successfully deleted',
            'data'=>$user
        ]);
    }

    public function index(){
        return response()->json(User::all());
    }
}
