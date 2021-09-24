<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

   // protected $appends = ['to_time'];
    public function employee(){
        return $this->belongsTo(User::class,'employee_id')->withTrashed();

        }
        public function driver(){
            return $this->belongsTo(User::class,'driver_id')->withTrashed();

            }
            public function time(){
                return $this->belongsTo(Time::class);

                }
        public function group(){
            return $this->hasMany(Group::class);
            }

            public function client(){
                return $this->hasMany(Client::class);
                }
        // public function getToTimeAttribute(){
        //     return Carbon::parse($this->time)->addHour()->toTimeString();
        // }

        // public function scopeAvailable($query,$time){
        //     return $query->where('to_time','<=',$time);
        // }
}
