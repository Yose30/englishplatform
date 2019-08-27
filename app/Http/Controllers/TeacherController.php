<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Teacher;
use App\User;
use App\Role;

class TeacherController extends Controller
{
    // Inicio para el teacher
    public function index(){
        return view('teacher.inicio');
    }

    // ADMINISTRADOR
    // Mostrar todos los profesores
    public function show(){
        $teachers = User::where('role_id', Role::TEACHER)->with('teacher')->orderBy('name','asc')->get();
        return response()->json($teachers);
    }

    // Guardar nuevo profesor
    public function store(Request $request){
        $this->validate_form($request);
        
        $name = $request->name;
        $unir = str_slug($name, "");
        $number = rand(0, 9000);
        $username = $unir.'-'.$number;
        
        try {
            \DB::beginTransaction();
            // Crear usuario
            $user = User::create([
                'role_id'   => Role::TEACHER,
                'name'      => $name,
                'last_name' => $request->last_name,
                'email'     => $request->email,
                'telephone' => $request->telephone,
                'username'  => $username,
                'password'  => bcrypt($username),
            ]);

            // Crear profesor
            $teacher = Teacher::create(['user_id' => $user->id]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($exception->getMessage());
        }
        return response()->json($user);
    }

    // Actualizar datos del profesor
    public function update(Request $request){
        $this->validate_form($request);
        try {
            \DB::beginTransaction();
            $user = User::whereId($request->id)->first();
            // Actualizar usuario
            $user->update([
                'name'      => $request->name,
                'last_name' => $request->last_name,
                'email'     => $request->email,
                'telephone' => $request->telephone
            ]);

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($exception->getMessage());
        }
        return response()->json($user);
    }

    // Validar datos
    public function validate_form($request){
        $this->validate($request, [
            'name'      => 'min:3|max:60|required|string',
            'last_name' => 'min:6|max:60|required|string',
            'email'     => 'min:8|max:60|required|email',
            'telephone' => 'min:7|max:25|required|string'
        ]);
    }

    // Buscar profesor
    public function search_teacher(){
        $queryTeacher = Input::get('queryTeacher');
        $teachers = User::where('role_id', Role::TEACHER)
                        ->where('name','like','%'.$queryTeacher.'%')
                        ->get();
        return response()->json($teachers);
    }
}
