<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client ;
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
            return response()->json(['token'=>$token],200);
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
        public function createClient(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $client=new Client;
                    $client->name=$request->name;
                    $client->email=$request->email;
                    $client->mobile=$request->mobile;
                    $client->address=$request->address;
                    $client->save();
                    return response()->json(["success"=>"Successfully added client"],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function count(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $employee=UserRole::where('role','=',2)->get()->count();
                    $client=Client::all()->count();
                    return response()->json(['employee'=>$employee,'client'=>$client],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function allClient(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $clients=Client::all();
                    return response()->json($clients,200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }

}
