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
	Route::put('course/updatecourse/{id}', 'CoursesController@update');
	Route::post('course/setstudent', 'CoursesController@setStudent');
	Route::delete('course/delcourse/{id}', 'CoursesController@deleteCourse');
});

Route::group(['middleware' => ['auth','validstudent']], function () {
	
	/*inscripcion de alumnos*/
	Route::get('academicoffer', 'CoursesController@showAcademy');
	Route::get('listacademic', 'CoursesController@listOfferAcademy');
	Route::post('saveinscription', 'CoursesController@inscription');
	
	/*aula virtual alumno*/
	Route::get('mycourses', 'CoursesController@coursesViewStudent');
	Route::get('listmycourses', 'CoursesController@detailCoursesStudent');	
	Route::get('coursestudent/{id}', 'CoursesController@viewCourseDetail');	
	Route::get('coursestudent/listStreaming/{id}', 'StreamingsController@listStreamingStudent');
	Route::get('coursestudent/getFiles/{idcourse}', 'FilesController@getFilesCourse');
	Route::get('coursestudent/getExams/{idcourse}','ExamsController@listExamsStudent');
	Route::get('getQuestions/{idcourse}','ExamsController@listQuestions');
	Route::post('coursestudent/saveQuestions','AnswersController@store');	
});

/*vista examen*/
Route::get('coursestudent/exam/{idexam}/{course}','ExamsController@viewExamStudent')->middleware('auth','validexam','validstudent');

Route::group(['middleware' => ['auth','validteacher']], function () {

	/*aula virtual*/
	Route::get('aulavirtual/list', 'StreamingsController@index');
	Route::get('aulavirtual/listcourses', 'CoursesController@listCourseStreaming');
	Route::get('aulavirtual/liststreamings', 'StreamingsController@listStreamings');
	Route::get('aulavirtual/getstreaming', 'StreamingsController@getStreaming');
	Route::post('aulavirtual/eventsave', 'StreamingsController@saveEvent');
	Route::put('aulavirtual/eventmod/{id}', 'StreamingsController@updateStreaming');
	Route::put('aulavirtual/finevent/{id}', 'StreamingsController@finalizarEvento');
	Route::delete('aulavirtual/delevent/{id}', 'StreamingsController@deleteStreaming');

	/*gestor de archivos*/
	Route::get('aulavirtual/files', 'FilesController@index');
	Route::get('aulavirtual/listfiles/{id}', 'FilesController@getFiles');
	Route::post('aulavirtual/savefiles', 'FilesController@saveFiles');
	Route::post('aulavirtual/deletefile', 'FilesController@deleteFile');

	/*modulo de creacion de examenes*/
	Route::get('exams/list', 'ExamsController@index');
	Route::get('exams/create', 'ExamsController@create');
	Route::get('exams/listcourses', 'CoursesController@listCourseExams');
	Route::get('exams/getexams', 'ExamsController@listExams');
	Route::post('exams/save', 'ExamsController@store');
	Route::get('exams/{id}', 'ExamsController@show');
	Route::post('exams/consultexam', 'ExamsController@consultExam');
	Route::post('exams/update', 'ExamsController@update');
	Route::post('exams/updatestatus', 'ExamsController@setEstatus');
	Route::delete('exams/deletexam/{id}', 'ExamsController@destroy');

	/*evaluaciones*/
	Route::get('evaluations', 'EvaluationsController@index');
	Route::get('evaluations/listcourses', 'CoursesController@listCoursesTeacher');
	Route::get('evaluations/listexams/{id}', 'ExamsController@listExamsEvaluations');
	Route::get('evaluations/listevaluations/{course}/{exam}', 'EvaluationsController@listEvaluations');
	Route::get('evaluations/getAnswer/{answer}', 'EvaluationsController@getExamAnswer');

	

});

/*PENDIENTE*/
/*profesores y administradores*/
Route::group(['middleware' => ['auth']], function () {
	Route::post('course/liststudents', 'CoursesController@getStudents');
});

/*profesores y administradores y alumnos*/	
Route::group(['middleware' => ['auth']], function () {
	Route::post('course/datacourse', 'CoursesController@editCourse');
});