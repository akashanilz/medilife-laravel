<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The appointments that belong to the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'groups');
    }

    public function appointment($id)
    {
        return $this->appointments()->wherePivot('appointment_id', $id)->first();
        // $query->appointments()->wherePivot('appointment_id',$id);
    }

}
