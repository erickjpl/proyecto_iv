<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\User;
use \App\Profile;
use \App\Course;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendMail;
use Session;

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
     * los cursos para el administrador ]
     * @return [] []
     */
    public function viewListCourses(){
        return View::make('courses.list');
    }


    /**
     * [listCourses consulta los cursos para el reporte de dataTables
     * cursos administrador]
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
                          <button type="button" class="acc_act btn btn-warning btn-xs" data-course="'.$id.'">Lista de Alumnos</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-course="'.$id.'">Eliminar</button>';

                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);
    }


    /**
     * [listOfferAcademy consulta que lista los cursos 
     * para que el estudiante se inscriba]
     * @return [type] [description]
     */
    public function listOfferAcademy(){
          $objCourse= new Course();
          $objUser= new User();
          $data=$objCourse->getCourse('',true);
          if(!empty($data)){
             foreach ($data as $key => $value) {
                $teachers=array();
                $id=$value->id;
                $start_date=explode(' ',$value->start_date);
                $end_date=explode(' ',$value->end_date);
                $value->start_date=date("d/m/Y",strtotime($start_date[0])).' '.date("h:i:s A",strtotime($start_date[1])); 
                $value->end_date=date("d/m/Y",strtotime($end_date[0])).' '.date("h:i:s A",strtotime($end_date[1])); 
                $relteacher=$objCourse->relationshipusers($id,'T');
                foreach ($relteacher as $k => $v) {
                    $data_user=$objUser->getUsers($v->user_id);
                    $nombre=$data_user[0]->name.' '.$data_user[0]->lastname;
                    array_push($teachers,$nombre);                   
                }
                $value->teacher=implode(',',$teachers);
             }
          }
          $resp["courses"][]=$data;
          return response()->json($resp);
    }


    /**
     * [editCourse funcion que solicita la data del curso para modificarlo]
     * @param  [int] $id [id del curso]
     * @return [json]     []
     */
    public function editCourse(Request $request)
    {     
        $objCourse= new Course();
        $id_course=base64_decode($request->id);
        $data_course=$objCourse->getCourse($id_course);
        $data_teacher=$objCourse->relationshipusers($id_course,'T');
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
     * [showCourse vista del curso para modificar
     * cursos administrador]
     * @param  [int] $id [id del curso]
     */
    public function showCourse($id)
    {   
        return View::make('courses.create',['id' =>$id]);
    }

    
    /**
     * [store funcion para guardar el curso 
     * modulo curso administrador]
     * @param  Request $request [objeto del formulario]
     * @return [json]           []
     */
    public function store(Request $request)
    {
        $objCourse=new Course();
        $save=$objCourse->saveCourse($request);
        return response()->json($save);
    }

    /**
     * [update fucnion que modifica el curso]
     * @param  Request $request [objeto del formulario]
     * @param  [int]  $id      [id del curso]
     * @return []           []
     */
    public function update(Request $request, $id)
    {   
        $objCourse= new Course();
        $id_course=base64_decode($id);
        $update=$objCourse->updateCourse($request, $id_course);
        return response()->json($update);
    }

    /**
     * [showAcademy vista para listar las ofertas de cursos 
     * para que el estudiante se inscriba ]
     * @return [type] [description]
     */
    public function showAcademy(){
       return View::make('courses.academic_offer');
    }

    /**
     * [inscription funcion que guarda la inscricion del alumno en el curso]
     * @param  Request $request [objeto del formulario]
     * @return [type]           [description]
     */
    public function inscription(Request $request){
        $objCourse= new Course();
        $objUser= new User();
        $arr=array();
        $course=base64_decode($request->course);
        $id_user=$request->session()->get('id');
        $user=$objUser::find($id_user);
        $mail=$user->email;
        $name=$user->name.' '.$user->lastname;       
        $data_mail=array();
        $data_course=$objCourse->getCourse($course);
        $start_date=date("d/m/Y h:i:s A",strtotime($data_course[0]->start_date)); 
        $end_date=date("d/m/Y h:i:s A",strtotime($data_course[0]->end_date)); 
        $data_mail["start_date"]=$start_date;
        $data_mail["end_date"]=$end_date;
        $data_mail["name_course"]=strtoupper($data_course[0]->name);
        $data_mail["name_user"]=$user->name;
        $user_course=$objCourse->relationshipusers($course,'S',$id_user);
        if(count($user_course)==0){
            array_push($arr,$id_user);
            $insert=$objCourse->insertRelationshipUsers($arr,$course,'S','false');
            if($insert==true){
                try {
                     Mail::to($mail,$name)->send(new SendMail($data_mail,'msg_inscription'));
                     $insert=$objCourse->returnOper(true);
                }catch (Exception $e) {
                    Log::error('COD: Falla Mail LINE: '.$ex->getLine().' FILE: '.$ex->getFile()); 
                    $error['cod']='Falla Mail';
                    $error['oper']=false;
                    return response()->json($error);
                }                
            }
            return response()->json($insert);
        }else{
            return $objCourse->returnOper(false,400);
        }

    }

    /**
     * [getStudents lista de estudiantes inscrito en un curso]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getStudents(Request $request){
        $objCourse= new Course();
        $id_course=base64_decode($request->course);
        $data=$objCourse->relationshipusers($id_course,'S');
        return response()->json($data);
        
    }

    /**
     * [setStudent description]
     * @param Request $request [description]
     */
    function setStudent(Request $request){
           $objUser= new User();
           $objCourse= new Course();
           $id_course=base64_decode($request->course_id);
           $id_user=base64_decode($request->user_id);
           $user=$objUser::find($id_user);
           $mail=$user->email;
           $name=$user->name.' '.$user->lastname;       
           $data_mail=array();
           $data_course=$objCourse->getCourse($id_course);
           $start_date=date("d/m/Y h:i:s A",strtotime($data_course[0]->start_date)); 
           $end_date=date("d/m/Y h:i:s A",strtotime($data_course[0]->end_date)); 
           $data_mail["start_date"]=$start_date;
           $data_mail["end_date"]=$end_date;
           $data_mail["name_course"]=strtoupper($data_course[0]->name);
           $data_mail["name_user"]=$user->name;
           $notif_mail=$request->notif;
           $active=$request->user_status;
           $data=$objCourse->getIdRelationshipUsers($id_course,$id_user);
           $id_rel=$data[0]->id;
           $arr=array();
           $arr['status']=$active;
           $obj = (object) $arr;
           $update_estatus=$objCourse->updateRelationshipUsers($id_rel,$obj);
           if($update_estatus["oper"]==true){
             if($notif_mail=='true'){
                $plantilla='msg_confinscription';
                if($active==='false'){
                  $plantilla='msg_bajainscription';  
                }
                try {
                     Mail::to($mail,$name)->send(new SendMail($data_mail,$plantilla));
                }catch (Exception $e) {
                    Log::error('COD: Falla Mail LINE: '.$ex->getLine().' FILE: '.$ex->getFile()); 
                    $error['cod']='Falla Mail';
                    $error['oper']=false;
                    return response()->json($error);
                } 
             }
           }
           return response()->json($update_estatus);
        }

        /**
         * [deleteCourse Funcion que elimina el curso 
         * mod administrador]
         * @param  Request $request [description]
         * @param  [type]  $id      [description]
         * @return [type]           [description]
         */
        function deleteCourse(Request $request, $id){
            $objCourse=new Course();
            $id_course=base64_decode($id);
            $data_course=$objCourse::find($id_course);
            $status=$data_course->status;
            if($status=='false'){
                $data_rel_course=$objCourse->relationshipusers($id_course,'S');
                if(count($data_rel_course)>0){
                    $val=true;
                    foreach ($data_rel_course as $key => $value) {
                       if($value->status=='true'){
                            $val=false;
                       }
                    }
                    if($val==false){ //hay un estudiante activo
                        return $objCourse->returnOper(false,'Hay Estudiantes Activos en el curso');
                    }else{
                        $delete=$objCourse->deleteCourse($id_course);
                        return response()->json($delete);
                    }
                }else{
                    $delete=$objCourse->deleteCourse($id_course);
                    return response()->json($delete);
                }
            }else{
                return $objCourse->returnOper(false,'Inactivar Curso'); 
            }
        }

        /**
         * [listCourseStreaming lista de cursos por profesor ]
         * @param  Request $request [description]
         * @return [type]           [description]
         */
        function listCourseStreaming(Request $request){
            $objCourse=new Course();
            $id_user=base64_decode($request->user);
            $data_courses=$objCourse->listCoursesStreaming($id_user);
            return response()->json($data_courses);
        }


        /**
         * [listCourseExams lista de cursos habilitados 
         * para crear examenes]
         * @return [type] [description]
         */
        function listCourseExams(){
            $user=Session::get('id');
            $objCourse=new Course();
            $data_courses=$objCourse->listCoursesExams($user);
            return response()->json($data_courses);
        }

        /**
         * [listCoursesTeacher description]
         * @return [type] [description]
         */
        function listCoursesTeacher(){
            $user=Session::get('id');
            $objCourse=new Course();
            $data=$objCourse->listCoursesTeacher($user);
            return response()->json($data[""]);
        }


        /**
         * [coursesViewStudent vista de Cursos 
         * inscritos por el estudiante]
         * @return [type] [description]
         */
        function coursesViewStudent(){
            $user=Session::get('id');
            $user=base64_encode($user);
            return View::make('coursestudent.listcourses')->with(['user'=>$user]);
        }

        /**
         * [detailCourseStudent Lista de curos 
         * inscritos por ell estudiante]
         * @return [type] [description]
         */
        function detailCoursesStudent(){
            $objCourse=new Course();
            $objUser= new User();
            $user=Session::get('id');
            $data=$objCourse->listCoursesStudent($user);

            if(count($data)>0){
                $resp=array();
                foreach ($data as $key=>$value) {
                    foreach ($value as $val) {
                        $val->start_date=date("d/m/Y h:i:s A",strtotime($val->start_date)); 
                        $val->end_date=date("d/m/Y h:i:s A",strtotime($val->end_date));
                        $teachers=array();
                        $relteacher=$objCourse->relationshipusers($val->id,'T');
                        foreach ($relteacher as $k => $v) {
                            $data_user=$objUser->getUsers($v->user_id);
                            $nombre=$data_user[0]->name.' '.$data_user[0]->lastname;
                            array_push($teachers,$nombre);                   
                        }
                        $val->teacher=implode(',',$teachers);
                        $resp[]=$val;
                    }
                }
            }
            return response()->json($resp);
        }

        /**
         * [viewCourseDetail vista del Aula Interactiva]
         * @param  [string] $id [id del curso]
         * @return []     []
         */
        function viewCourseDetail($id){
           return View::make('coursestudent.classroom',['id' =>$id]);
        }

        

}
