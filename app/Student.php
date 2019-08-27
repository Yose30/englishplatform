<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Group;
use App\User;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'group_id'
    ];

    // 1 a 1 (Inversa)
    // Un usuario solo puede tener un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 1 a * (Inversa)
    // Un estudiante solo puede pertenecer a un grupo
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
