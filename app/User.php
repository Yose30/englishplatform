<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Administrator;
use App\Student;
use App\Teacher;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'last_name', 'email', 'telephone', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 1 a 1 (Inversa)
    // Un usuario solo puede tener un rol
    public function role(){
        return $this->belongsTo(Role::class);
    }

    // 1 a 1
    // Un usuario solo puede ser administrador
    public function administrator(){
    	return $this->hasOne(Administrator::class);
    } 

    // 1 a 1
    // Un usuario solo puede ser teacher
    public function teacher(){
    	return $this->hasOne(Teacher::class);
    } 

    // 1 a 1
    // Un usuario solo puede ser student
    public function student(){
    	return $this->hasOne(Student::class);
    } 

}
