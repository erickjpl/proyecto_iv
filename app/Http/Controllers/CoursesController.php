<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\User;
use \App\Profile;
use \App\Course;

class CoursesController extends Controller
{


    /**
     * [formulario de creacion de cursos]
     * @return [] []
     */
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
     * [viewListCourses tabla donde se muestra 
     * los contratos ]
     * @return [] []
     */
    public function viewListCourses(){
        return View::make('courses.list');
    }


    /**
     * [listCourses consulta los cursos para el reporte de dataTables]
     * @return [json] []
     */
     public function listCourses(){
        $objCourse= new Course();
        $data=$objCourse->getCourse();
        $resp["data"]=array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                 $id=base64_encode($value->id);
                 $value->status=($value->status=='true')?'Si':'No';
                 $value->start_date=date("d/m/Y h:i:s A",strtotime($value->start_date)); 
                 $value->end_date=date("d/m/Y h:i:s A",strtotime($value->end_date)); 
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-course="'.$id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-warning btn-xs" data-course="'.$id.'">Lista de Alumnos</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-course="'.$id.'">Eliminar</button>';

                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);
    }


    /**
     * [editCourse description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editCourse(Request $request)
    {     
        $objCourse= new Course();
        $id_course=base64_decode($request->id);
        $data_course=$objCourse->getCourse($id_course);
        $data_teacher=$objCourse->relationshipteacher($id_course);
        $arr=array();
        foreach ($data_course as $key => $value) {
            $time_ini=explode(' ',$value->start_date);
            $time_fin=explode(' ',$value->end_date);
            $h_inicio=date("h:i:sA",strtotime($time_ini[1])); 
            $h_fin=date("h:i:sA",strtotime($time_fin[1])); 
            $f_inicio=date("d-m-Y",strtotime($time_ini[0])); 
            $f_fin=date("d-m-Y",strtotime($time_fin[0])); 
            $f_inicio=str_replace('-','/',$f_inicio);
            $f_fin=str_replace('-','/',$f_fin);
            $d["name"]=$value->name;
            $d["status"]=$value->status;
            $d["streaming"]=$value->streaming;
            $d["temary"]=$value->temary;
            $d["exams"]=$value->exams;
            $d["f_inicio"]=$f_inicio;
            $d["f_fin"]=$f_fin;
            $d["h_inicio"]=$h_inicio;
            $d["h_fin"]=$h_fin;
            $arr[]=$d;
        }
        $data["course"]=$arr;
        $data["teachers"]=$data_teacher[0];
        return response()->json($data);   
    }

    /**
     * [showCourse description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showCourse($id)
    {   
        return View::make('courses.create',['id' =>$id]);
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


    public function update(Request $request, $id)
    {   
        $objCourse= new Course();
        $id_course=base64_decode($id);
        $update=$objCourse->updateCourse($request, $id_course);
        return response()->json($update);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
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
    /*public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        //
    }*/
}
