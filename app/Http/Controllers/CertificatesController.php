<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View; 
use App\Course;
use App\Answer;
use App\Certificate;


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
        if(count($data)>0){
            $data=$data[""];
        }
        return response()->json($data);
    }


    public function listStudents($course){
       $objAnswer=new Answer();
       $course=base64_decode($course);
       $data["data"]='';
       if($course!='a'){
          $list_students=$objAnswer->listStudentCertif($course);
          if(count($list_students)>0){
                foreach ($list_students as $value) {
                foreach ($value as $val) {
                    $val->name=$val->name.' '.$val->lastname;
                     $val->actions='<div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-warning ">
                            <i class="glyphicon glyphicon-saved"></i>&nbsp;
                            <input name="users[]" class="select-users" value="'.$val->id.'" type="checkbox" autocomplete="off" >
                            <span class="glyphicon glyphicon-ok"></span>
                        </label>
                     </div>';
                    $val->qualification='<i title="Aprobado" class="glyphicon glyphicon-ok aprobexam"></i>';
                }              
              }
              $data["data"]=$list_students[""];
          }
       }
       return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objCertif=new Certificate();
        $course=base64_decode($request->course);
        $users=explode(',',$request->users);
        if(is_array($users)){
            foreach ($users as $key => $value) {
                $data=array();
                $user=base64_decode($value);
                $data["user_id"]=$user;
                $data["course_id"]=$course;
                $data["certificate"]='true';
                $data["created_at"]=date("Y-m-d H:i:s");
                $insert=$objCertif->insertCertificates($data);
                if($insert==true){
                    //envio de email;
                }else{
                    return response()->json($insert);
                }
            }
            return $objCertif->returnOper(true); 
        }else{
            return $objCertif->returnOper(false,'Error en el tipo de Usuario a ingresar'); 
        }        
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
