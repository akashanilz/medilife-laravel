<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client ;
use App\Models\Location;
use App\Models\User;
use App\Models\UserDetail;
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
        public function createLocation(Request $request){
            $location= new Location();
            $location->name=$request->name;
            $location->save();
            return response()->json(['location'=>$location],200);
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
                    return response()->json(["success"=>"Successfully added client","client"=>$client],200);
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
                    $driver=UserRole::where('role','=',3)->get()->count();
                    $client=Client::all()->count();
                    return response()->json(['employee'=>$employee,'client'=>$client,'driver'=>$driver],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function allClients(){
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
        public function editClient(Request $request, $id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $client=Client::find($id);
                    if($request->name){
                        $client->name=$request->name;
                    }
                    if($request->email){
                        $client->email=$request->email;
                    }
                    if($request->mobile){
                        $client->mobile=$request->mobile;
                    }
                    if($request->address){
                        $client->address=$request->address;
                    }
                    $client->update();
                    return response()->json(['client'=>$client],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function viewClient($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $client=Client::find($id);
                    return response()->json(['client'=>$client],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function deleteClient($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    Client::find($id)->delete();
                    return response()->json(['message'=>'Deleted Successfully'],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function createEmployee(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=new User();
                    $user_details= new UserDetail();
                    $user->name=$request->name;
                    $user->email=$request->email;
                    $user->password=Hash::make($request->password);
                    $user->save();
                    $user_details->user_id=$user->id;
                    $user_details->doj=$request->doj;
                    $user_details->mobile=$request->mobile;
                    $user_details->address=$request->address;
                    $user_details->dob=$request->dob;
                    $user_details->location_id=$request->location_id;
                    $user_details->age=$request->age;
                    $user_details->designation=$request->designation;
                    $user_details->save();
                    $role=new UserRole();
                    $role->user_id=$user->id;
                    $role->role=2;
                    $role->save();
                    $token =$user->createToken('medilife')->accessToken;
                    return response()->json(['user'=>$user,'user_details'=>$user_details,'token'=>$token],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function allEmployees(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user = User::whereHas('role',function($q){
                        $q->where('role','=','2');
                    })->get();
                    $clients=UserRole::where('role','=','2')->get();
                    return response()->json($user,200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function editEmployee(Request $request, $id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=User::find($id);
                    $user_details= UserDetail::where('user_id','=',$id)->first();
                    if($request->name){
                        $user->name=$request->name;
                    }
                    if($request->email){
                        $user->email=$request->email;
                    }
                    if($request->password){
                        $user->password=Hash::make($request->password);;
                    }
                    if($request->doj){
                        $user_details->doj=$request->doj;
                    }
                    if($request->mobile){
                        $user_details->mobile=$request->mobile;
                    }
                    if($request->address){
                        $user_details->address=$request->address;
                    }
                    if($request->dob){
                        $user_details->dob=$request->dob;
                    }
                    if($request->age){
                        $user_details->age=$request->age;
                    }
                    if($request->designation){
                        $user_details->designation=$request->designation;
                    }
                    if($request->location_id){
                        $user_details->location_id=$request->location_id;
                    }
                    $user_details->update();
                    $user->update();
                    return response()->json(['user'=>$user],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function viewEmployee($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=User::find($id);
                    return response()->json(['user'=>$user],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function deleteEmployee($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    User::find($id)->delete();
                    return response()->json(['message'=>'Deleted Successfully'],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }

        /**Driver */
        public function createDriver(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=new User();
                    $user_details= new UserDetail();
                    $user->name=$request->name;
                    $user->email=$request->email;
                    $user->password=Hash::make($request->password);
                    $user->save();
                    $user_details->user_id=$user->id;
                    $user_details->doj=$request->doj;
                    $user_details->mobile=$request->mobile;
                    $user_details->address=$request->address;
                    $user_details->dob=$request->dob;
                    $user_details->location_id=$request->location_id;
                    $user_details->age=$request->age;
                    $user_details->designation=$request->designation;
                    $user_details->save();
                    $role=new UserRole();
                    $role->user_id=$user->id;
                    $role->role=3;
                    $role->save();
                    $token =$user->createToken('medilife')->accessToken;
                    return response()->json(['token'=>$token],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function allDrivers(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user = User::whereHas('role',function($q){
                        $q->where('role','=','3');
                    })->get();
                    $clients=UserRole::where('role','=','3')->get();
                    return response()->json($user,200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function editDriver(Request $request, $id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=User::find($id);
                    $user_details= UserDetail::where('user_id','=',$id)->first();
                    if($request->name){
                        $user->name=$request->name;
                    }
                    if($request->email){
                        $user->email=$request->email;
                    }
                    if($request->password){
                        $user->password=Hash::make($request->password);;
                    }
                    if($request->doj){
                        $user_details->doj=$request->doj;
                    }
                    if($request->mobile){
                        $user_details->mobile=$request->mobile;
                    }
                    if($request->address){
                        $user_details->address=$request->address;
                    }
                    if($request->dob){
                        $user_details->dob=$request->dob;
                    }
                    if($request->age){
                        $user_details->age=$request->age;
                    }
                    if($request->designation){
                        $user_details->designation=$request->designation;
                    }
                    if($request->location_id){
                        $user_details->location_id=$request->location_id;
                    }
                    $user_details->update();
                    $user->update();
                    return response()->json(['user'=>$user],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function viewDriver($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $user=User::find($id);
                    return response()->json(['user'=>$user],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function deleteDriver($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    User::find($id)->delete();
                    return response()->json(['message'=>'Deleted Successfully'],200);
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
