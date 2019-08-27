<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    // Roles
    const ADMINISTRATOR = 1;
    const TEACHER = 2;
    const STUDENT =3;

    //1 a 1
    //Un rol solo va a pertenecer a un solo usuario
    public function user(){
    	return $this->hasOne(User::class);
    } 
}
