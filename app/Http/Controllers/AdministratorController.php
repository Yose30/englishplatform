<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\User;
use App\Role;

class AdministratorController extends Controller
{
    //Inicio para el administrador
    public function index(){
        $groups = Group::with('teacher.user')->orderBy('name','asc')->get();
        return view('administrator.inicio', compact('groups'));
    }
}
