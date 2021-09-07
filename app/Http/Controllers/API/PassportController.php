<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassportController extends Controller
{
    public function register(Request $request){
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();
        $role=new UserRole();
        $role->user_id=$user->id;
        $role->role=1;
        $role->save();
        $token =$user->createToken('medilife')->accessToken;
        return response()->json(['token'=>$token],200);
    }
    public function login(Request $request){
        $credentials=[
            "email"=>$request->email,
            "password"=>$request->password
        ];
        if(auth()->attempt($credentials)){
            $token=auth()->user()->createToken('medilife')->accessToken;
            return response()->json(['name'=>$token],200);
        }
        else{
            return response()->json(['error'=>'unAutorized'],401);
        }
    }
        public function dashboard(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                return response()->json(['role'=>$roles->role,'user'=>auth()->user()],200);
            }
            else{
                return response()->json(['error'=>"Access denied"],401);
            }

        }

}
