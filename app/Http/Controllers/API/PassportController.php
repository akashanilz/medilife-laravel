<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client ;
use App\Models\Group;
use App\Models\Location;
use App\Models\Time;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\New_;

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
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                  $location= new Location();
                  $location->name=$request->name;
                  $location->save();
                  return response()->json(['location'=>$location],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function deleteLocation($id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    Location::find($id)->delete();
                    return response()->json(['Deleted successfully'],200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function editLocation(Request $request,$id){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $location=Location::find($id);
                    $location->name=$request->name;
                    $location->update();
                    return response()->json($location,200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function allLocations(){
            if(auth()->user()){
               $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
               if($roles->role == 1){
                   $locations=Location::all();
                   return response()->json($locations,200);
               }
                 else{
                   return response()->json(["Error"=>"Unauthorized"],401);
               }
            }
              else{
                   return response()->json(["Error"=>"Unauthorized"],401);
               }
       }
        public function createClient(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1 || $roles->role == 2){
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
                    $appointments_not_confirmed=Appointment::where('confirm','=',0)->count();
                    $appointments_confirmed=Appointment::where('confirm','=',1)->count();
                    return response()->json(['employee'=>$employee,'client'=>$client,'driver'=>$driver,'appointments_not_confirmed'=>$appointments_not_confirmed,'appointments_confirmed'=>$appointments_confirmed],200);
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
                if($roles->role == 1 || $roles->role == 2 ){
                    $client=Client::find($id);
                    if($request->name){
                        $client->name=$request->name;
                    }
        
                        $client->status=1;
                    
                    if($request->email){
                        $client->email=$request->email;
                    }
                    if($request->contact_number){
                        $client->contact_number=$request->contact_number;
                    }
                    if($request->address){
                        $client->address=$request->address;
                    }
                    if($request->whatsapp_number){
                        $client->whatsapp_number=$request->whatsapp_number;
                    }
                    if($request->building_name){
                        $client->building_name=$request->building_name;
                    }
                    if($request->room_number){
                        $client->room_number=$request->room_number;
                    }
                    if($request->tat){
                        $client->tat=$request->tat;
                    }
                    if($request->id_number){
                        $client->id_number=$request->id_number;
                    }
                    if($request->id_type){
                        $client->id_type=$request->id_type;
                    }
                    if($request->alhasna_number){
                        $client->alhasna_number=$request->alhasna_number;
                    }
                    if($request->emirate){
                        $client->emirate=$request->emirate;
                    }
                    if($request->type_of_client){
                        $client->type_of_client=$request->type_of_client;
                    }
                    if($request->client_category){
                        $client->client_category=$request->client_category;
                    }
                    if($request->occupation_uae){
                        $client->occupation_uae=$request->occupation_uae;
                    }
                    if($request->company_name_uae){
                        $client->company_name_uae=$request->company_name_uae;
                    }
                    if($request->company_address_uae){
                        $client->company_address_uae=$request->company_address_uae;
                    }
                    if($request->emirate_uae){
                        $client->emirate_uae=$request->emirate_uae;
                    }
                    if($request->country_visit_travel){
                        $client->country_visit_travel=$request->country_visit_travel;
                    }
                    if($request->arriving_visit_travel){
                        $client->arriving_visit_travel=$request->arriving_visit_travel;
                    }
                    if($request->arriving_date_travel){
                        $client->arriving_date_travel=$request->arriving_date_travel;
                    }
                    if($request->departure_date_travel){
                        $client->departure_date_travel=$request->departure_date_travel;
                    }
                    if($request->stay_length_travel){
                        $client->stay_length_travel=$request->stay_length_travel;
                    }
                    if($request->institution_student){
                        $client->institution_student=$request->institution_student;
                    }
                    if($request->type_student){
                        $client->type_student=$request->type_student;
                    }
                    if($request->details_student){
                        $client->details_student=$request->details_student;
                    }
                    if($request->institution_type_student){
                        $client->institution_type_student=$request->institution_type_student;
                    }

                    if($request->location_student){
                        $client->location_student=$request->location_student;
                    }
                    if($request->camp_name_labour){
                        $client->camp_name_labour=$request->camp_name_labour;
                    }
                    if($request->address_camp_labour){
                        $client->address_camp_labour=$request->address_camp_labour;
                    }
                    if($request->supervisor_name_labour){
                        $client->supervisor_name_labour=$request->supervisor_name_labour;
                    }
                    if($request->supervisor_contact_number){
                        $client->supervisor_contact_number=$request->supervisor_contact_number;
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
                if($roles->role == 1 || $roles->role == 2){
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
                    $validator = Validator::make($request->all(),[
                        'name'=>'required|max:100',
                        'email'=>'required|unique:users,email',
                        'password'=>'required|min:6',
                        'mobile'=>'required|unique:user_details,mobile',
                    ]
                    );
                    if($validator->fails()){
                        return response()->json(['validation_error'=>$validator->messages()],401);
                    }else{
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
                       // $user_details->location_id=$request->location_id;
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
                    $validator = Validator::make($request->all(),[

                        'email'=>'unique:users,email',
                        //'password'=>'min:6',
                        'mobile'=>'unique:user_details,mobile',
                    ]
                    );
                    if($validator->fails()){
                        return response()->json(['validation_error'=>$validator->messages()],401);
                    }
                    else{

                    }

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
                    // if($request->location_id){
                    //     $user_details->location_id=$request->location_id;
                    // }
                    $user_details->update();
                    $user->update();
                    return response()->json(['user'=>$user,'user_details'=>$user_details],200);
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
                    $user_details=UserDetail::where('user_id','=',$id)->first();
                    return response()->json(['user'=>$user,'user_details'=>$user_details],200);
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
                    UserRole::where('user_id','=',$id)->first()->delete();
                    UserDetail::where('user_id','=',$id)->first()->delete();
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
                    $validator = Validator::make($request->all(),[
                        'name'=>'required|max:100',
                        'email'=>'required|unique:users,email',
                        'password'=>'required|min:6',
                        'mobile'=>'required|unique:user_details,mobile',
                    ]
                    );
                    if($validator->fails()){
                        return response()->json(['validation_error'=>$validator->messages()],401);
                    }
                    else{
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
                       // $user_details->location_id=$request->location_id;
                        $user_details->age=$request->age;
                        $user_details->designation=$request->designation;
                        $user_details->save();
                        $role=new UserRole();
                        $role->user_id=$user->id;
                        $role->role=3;
                        $role->save();
                        $token =$user->createToken('medilife')->accessToken;
                        return response()->json(['user'=>$user,'user_details'=>$user_details,'token'=>$token],200);

                    }
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
                    $validator = Validator::make($request->all(),[

                        'email'=>'unique:users,email',
                       // 'password'=>'min:6',
                        'mobile'=>'unique:user_details,mobile',
                    ]
                    );
                    if($validator->fails()){
                        return response()->json(['validation_error'=>$validator->messages()],401);
                    }
                    else{

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
                        // if($request->designation){
                        //     $user_details->designation=$request->designation;
                        // }
                        // if($request->location_id){
                        //     $user_details->location_id=$request->location_id;
                        // }
                        $user_details->update();
                        $user->update();
                        return response()->json(['user'=>$user,'user_details'=>$user_details],200);

                    }
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
                    $user_details=UserDetail::where('user_id','=',$id)->first();
                    return response()->json(['user'=>$user,'user_details'=>$user_details],200);
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
                    UserRole::where('user_id','=',$id)->first()->delete();
                    UserDetail::where('user_id','=',$id)->first()->delete();
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
        /**Appointment */
        public function timeRange(){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $time=Time::all();
                    return response()->json($time,200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
        }
        public function getFreeUsers(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                   $date=$request->date;
                   $time=$request->time;
                //    $to_time = Carbon::parse($time)->addHour()->toTimeString();
                //    // dd($to_time);
                   $driver = User::whereHas('role',function($q){
                   $q->where('role',3);
                      })->whereDoesntHave('driverAppointments', function ($query) use($date,$time)  {
                      $query->where('date','=',$date)->where('time_id','=', $time);
                   })->get();
                  $employee = User::whereHas('role',function($q){
                  $q->where('role',2);
                    })->whereDoesntHave('employeeAppointments', function ($query) use($date,$time)  {
                    $query->where('date','=',$date)->where('time_id','=', $time);
                    })->get();
                    return response()->json(['employee'=>$employee,'driver'=>$driver],200);
                }
                 else{
                 return response()->json(["Error"=>"Unauthorized"],401);
                 }
            }
        }
        public function createAppointment(Request $request){
            if(auth()->user()){
                $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
                if($roles->role == 1){
                    $appointment=new Appointment();
                    $appointment->remark=$request->remark;
                    $appointment->employee_id=$request->employee;
                    $appointment->driver_id=$request->driver;
                    $appointment->time_id=$request->time;
                    $appointment->date=$request->date;
                    $appointment->location=$request->location;
                    $appointment->number_of_test=$request->number_of_test;
                    $appointment->cost_per_test=$request->cost_per_test;
                    $appointment->net_amount=$request->net_amount;
                    if($appointment->disclosure){
                        $appointment->disclosure=1;
                    }
                    $appointment->payment_type=$request->payment_type;
                    $appointment->sales_office=auth()->user()->email;
                    $appointment->save();
                    foreach($request->clients as $key => $clients){
                        $client=new Client();
                        $group=new Group();
                        $client->whatsapp_number=$clients['whatsapp_number'];
                        $client->contact_number=$clients['contact_number'];
                        $client->name=$clients['name'];
                        $client->building_name=$clients['building_name'];
                        $client->room_number=$clients['room_number'];
                        $client->tat=$clients['tat'];
                        $client->id_number=$clients['id_number'];
                        $client->id_type=$clients['id_type'];
                        $client->email=$clients['email'];
                        $client->alhasna_number=$clients['alhasna_number'];
                        $client->save();
                        $group->appointment_id=$appointment->id;
                        $group->client_id=$client['id'];
                        $group->save();
                    }
                    return response()->json("Success",200);
                }
                else{
                    return response()->json(["Error"=>"Unauthorized"],401);
                }
        }
        else{
            return response()->json(["Error"=>"Unauthorized"],401);
        }
    }
    public function appointmentsNotConfirmed(){
        if(auth()->user()){
            $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
            if($roles->role == 1){
                $appointment= Appointment::where('confirm','=',0)->with('time','employee','driver')->get();
                return response()->json($appointment,200);
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
    }
    else{
        return response()->json(["Error"=>"Unauthorized"],401);
    }
}
public function appointmentsConfirmed(){
    if(auth()->user()){
        $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
        if($roles->role == 1){
            $appointment= Appointment::where('confirm','=',1)->with('time','employee','driver')->get();
            return response()->json($appointment,200);
        }
        else{
            return response()->json(["Error"=>"Unauthorized"],401);
        }
}
else{
    return response()->json(["Error"=>"Unauthorized"],401);
}
}
    public function confirmAppointment($id){
        if(auth()->user()){
            $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
            if($roles->role == 1){
                $appointment= Appointment::find($id);
                $employee= $appointment->employee;
                $driver= $appointment->driver;
                $empmail=$employee->email;
                $drivermail=$driver->email;
                $data = array('employee'=>$employee->name,'driver'=>$driver->name,'time'=>$appointment->time->time,'date'=>$appointment->date,'location'=>$appointment->location);
                Mail::send('mail', $data, function($message)use($empmail) {
                   $message->to($empmail, 'Medilife')->subject
                      ('Task Assigned');

                });
                Mail::send('mail', $data, function($message)use($drivermail) {
                    $message->to($drivermail, 'Medilife')->subject
                       ('Task Assigned');

                 });
                 $appointment->confirm=1;
                 $appointment->update();
              // dd($employee,$driver);
               return response()->json("success",200);
            }
            else{
                return response()->json(["Error"=>"Unauthorized"],401);
            }
    }else{
        return response()->json(["Error"=>"Unauthorized"],401);
    }
}
  public function getMyTasks(){
    if(auth()->user()){
        $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
        if($roles->role == 2){
          $appointments=Appointment::where('employee_id','=',auth()->user()->id)->where('confirm','=',1)->with('time','employee','driver')->latest()->get();;
           return response()->json($appointments,200);
        }
        if($roles->role == 3){
            $appointments=Appointment::where('driver_id','=',auth()->user()->id)->where('confirm','=',1)->with('time','employee','driver')->latest()->get();;
             return response()->json($appointments,200);
          }
  }else{
    return response()->json(["Error"=>"Unauthorized"],401);
}
  }
  public function findAppointment($id){
    if(auth()->user()){
        $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
        if($roles->role == 1  || $roles->role == 2 ){
            $group= Group::where('appointment_id','=',$id)->with('appointment','client')->get();
            $employee=Appointment::find($id)->employee;
            $appointment=Appointment::where('id','=',$id)->with('time')->get();
            $driver=Appointment::find($id)->driver;
            return response()->json(['appointment'=>$appointment,'group'=>$group,'employee'=>$employee,'driver'=>$driver],200);
        }
        else{
            return response()->json(["Error"=>"Unauthorized"],401);
        }
    }else{
        return response()->json(["Error"=>"Unauthorized"],401);
    }
  }
  public function changeAppointmentStatus($id){
    if(auth()->user()){
        $roles=UserRole::where('user_id','=',auth()->user()->id)->first();
        if($roles->role == 1  || $roles->role == 2 ){
            $appointment=Appointment::find($id);
            if($appointment->status==0){
                $appointment->status=1;
                $appointment->update();
                return response()->json($appointment,200);
            }
            if($appointment->status==1){
                $appointment->status=2;
                $appointment->update();
                return response()->json($appointment,200);
            }
            if($appointment->status==2){
                $appointment->status=3;
                $appointment->update();
                return response()->json($appointment,200);
            }
           }
        else{
            return response()->json(["Error"=>"Unauthorized"],401);
        }
    }else{
        return response()->json(["Error"=>"Unauthorized"],401);
    }
  }
  public function getMyCompletedTasks(){
    if(auth()->user()){
          $appointments=Appointment::where('employee_id','=',auth()->user()->id)->where('status','=',3)->with('time','employee','driver')->latest()->get();;
           return response()->json($appointments,200);
        }else{
            return response()->json(["Error"=>"Unauthorized"],401);
        }
  }
}
