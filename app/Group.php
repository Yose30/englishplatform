<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Teacher;
use App\Student;
use App\Lesson;

class Group extends Model
{
    protected $fillable = [
        'name', 'teacher_id', 'slug'
    ];

    // 1 a * (Inversa)
    // Un grupo solo pertence a un teacher
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    //1 a *
    //Un grupo puede tener muchas clases
    public function lessons(){
        return $this->hasMany(Lesson::class);
    } // PENDIENTE

    //1 a *
    //Un grupo puede tener muchos estudiantes
    public function studentS(){
        return $this->hasMany(Student::class);
    }
}
