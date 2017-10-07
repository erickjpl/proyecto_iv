<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\User;
use \App\Profile;
use \App\Course;

class CoursesController extends Controller
{



    public function index()
    {
        return View::make('courses.create');
    }


    /**
     * [listTeachers funcion que lista profesores]
     * @return [json] 
     */
    public function listTeachers(){
        $objUser= new User();
        $objProfile= new Profile();
        $profiles[]=$objProfile->pro_teacher;
        $data_user=$objUser->getUsers('',$profiles);
        return response()->json($data_user);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objCourse=new Course();
        $save=$objCourse->saveCourse($request);
        return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
