<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Inicio para el student
    public function index(){
        return view('student.inicio');
    }

    // Validar formulario de estudiante
    public function validate_form(Request $request){
        $this->validate($request, [
            'name'      => 'min:3|max:60|required|string',
            'last_name' => 'min:6|max:60|required|string',
            'email'     => 'min:8|max:60|required|email',
            'telephone' => 'min:7|max:25|required|string'
        ]);
        return response()->json(null, 200);
    }
}
