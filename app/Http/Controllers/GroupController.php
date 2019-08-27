<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Student;
use App\Teacher;
use App\Group;
use App\User;
use App\Role; 

class GroupController extends Controller
{
    //Obtener los grupos
    public function index(){
        $groups = Group::with('teacher.user')->orderBy('name','asc')->get();
        return view('administrator.inicio', compact('groups'));
    }

    //Crear un grupo
    public function store(Request $request){
        $count_groups = Group::count() + 1;
        $name_group = 'Grupo '.$count_groups;
        try {
            \DB::beginTransaction();
                $group = Group::create([
                    'name'          => $name_group,
                    'teacher_id'    => $request->teacher['id'],
                    'slug'          => str_slug($name_group, "-")
                ]);
                foreach($request->students as $student){
                    $name = $student['name'];
                    $unir = str_slug($name, "");
                    $number = rand(0, 9000);
                    $username = $unir.'-'.$number;
                    // Crear usuario
                    $user = User::create([
                        'role_id'   => Role::STUDENT,
                        'name'      => $name,
                        'last_name' => $student['last_name'],
                        'email'     => $student['email'],
                        'telephone' => $student['telephone'],
                        'username'  => $username,
                        'password'  => bcrypt($username),
                    ]);
                    // Crear estudiante
                    $student = Student::create([
                        'user_id'   => $user->id,
                        'group_id'  => $group->id
                    ]);
                }
                $grupo = Group::whereId($group->id)->with('teacher.user')->first();
            \DB::commit();
        }catch (Exception $e) {
            \DB::rollBack();
            return response()->json($exception->getMessage());
        }
        return response()->json($grupo);
    }

    public function show(){
        $group_id = Input::get('group_id');
        $group = Group::whereId($group_id)->with('teacher.user', 'students.user')->first();
        return response()->json($group);
    }

    
}
