<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View; 
use App\Course;
use App\Answer;


class CertificatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('certificates.list_exams');
    }


    public function listCourses(){
        $objCourse=new Course();
        $data=$objCourse->listCourseCertificates();
        return response()->json($data[""]);
    }


    public function listStudents($course){
       $objAnswer=new Answer();
       $course=base64_decode($course);
       $data["data"]='';
       if($course!='a'){
          $list_students=$objAnswer->listStudentCertif($course);
          foreach ($list_students as $value) {
                foreach ($value as $val) {
                    $val->name=$val->name.' '.$val->lastname;
                    
                    $val->actions='<input type="checkbox" name=users[] value='.$val->id.' class="select-users" />';
                    $val->qualification='<i title="Aprobado" class="glyphicon glyphicon-ok aprobexam"></i>';
                }
              
          }
          $data["data"]=$list_students[""];
       }
       
       return response()->json($data);
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
        //
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
