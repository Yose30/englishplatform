<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Group;
use App\User;

class Teacher extends Model
{
    protected $fillable = [
        'user_id'
    ];
    
    // 1 a 1 (Inversa)
    // Un usuario solo puede tener un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 1 a *
    // Un teacher puede tener muchos grupos
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
