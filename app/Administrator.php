<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Administrator extends Model
{
    protected $fillable = [
        'user_id'
    ];

    //1 a 1 (Inversa)
    //Un usuario solo puede tener un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }
}
