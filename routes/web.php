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

Route::group(['middleware' => ['auth']], function () {
 Route::get('/home', 'HomeController@index')->name('home');
});


Route::group(['middleware' => ['auth','validmoduser']], function () {
   	
   	/**Modulo de Usuarios**/
	Route::get('students', 'UsersController@indexStudents');
	Route::get('studentslist', 'UsersController@listStudents');
	Route::get('users', 'UsersController@indexUsers');
	Route::get('userslist', 'UsersController@listUsers');
	Route::get('profileslist', 'ProfilesController@getProfiles');
	Route::get('ocupationslist', 'OccupationController@getOcupations');
	Route::post('saveuser', 'UsersController@store');
	Route::get('getuser', 'UsersController@show');
	Route::put('moduser/{id}', 'UsersController@updateUser');
	Route::delete('deluser/{id}', 'UsersController@destroy');
	Route::post('setuser', 'UsersController@setEstatusUser');

	/*Modulo de Cursos*/
	Route::get('course/createcourse', 'CoursesController@index');
	Route::get('course/listcourse', 'CoursesController@viewListCourses');
	Route::get('course/teacherslist', 'CoursesController@listTeachers');
	Route::get('course/getcourses', 'CoursesController@listCourses');
	Route::get('course/{id}', 'CoursesController@showCourse');
	Route::post('course/savecourse', 'CoursesController@store');
	Route::post('course/datacourse', 'CoursesController@editCourse');
	Route::put('course/updatecourse/{id}', 'CoursesController@update');


	/*inscripcion de alumnos*/
	Route::get('academicoffer', 'CoursesController@showAcademy');
	Route::get('listacademic', 'CoursesController@listOfferAcademy');


	

	

});

