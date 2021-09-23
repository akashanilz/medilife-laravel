<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }
}
