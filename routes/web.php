<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//ADMINISTRATOR
Route::name('administrator.')->prefix('administrator')->middleware(['auth', 'role:1'])->group(function () {
    // CONTROLADOR ADMINISTRATOR

    // CONTROLADOR TEACHER
    // Obtener todos los profesores
    Route::get('/get_teachers', 'TeacherController@show')->name('getTeachers');
    // Agregar nuevo profesor
    Route::post('/save_teacher', 'TeacherController@store')->name('save_teacher');
    // Editar profesor
    Route::put('/update_teacher', 'TeacherController@update')->name('update_teacher');
    // Buscar profesor
    Route::get('/search_teacher', 'TeacherController@search_teacher')->name('search_teacher');

    // CONTROLADOR GROUP
    // Obtener todos los grupos
    Route::get('/inicio', 'GroupController@index')->name('inicio');
    // Guardar un grupo
    Route::post('/save_group', 'GroupController@store')->name('save_group');
    // Detalles del grupo
    Route::get('/details_group', 'GroupController@show')->name('details_group');

    // CONTROLADOR STUDENT
    // Validar formulario de student
    Route::post('/validate_student', 'StudentController@validate_form')->name('validate_student');
});

//TEACHER
Route::name('teacher.')->prefix('teacher')->middleware(['auth', 'role:2'])->group(function () {
    Route::get('/inicio', 'TeacherController@index')->name('inicio');
    
});

//STUDENT
Route::name('student.')->prefix('student')->middleware(['auth', 'role:3'])->group(function () {
    Route::get('/inicio', 'StudentController@index')->name('inicio');
});