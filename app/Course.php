<?php

namespace App;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * [saveCourse description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    function saveCourse($request){
        $id="";
        $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->h_inicio))); 
        $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->h_fin))); 
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_inicio))); 
        $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_fin))); 
        $streaming='false';
        $exams='false';
        $material=$request->material;
        $profesores=array();
        array_push($profesores,$request->profesor);
        if(in_array('mat_clvivo',$material)){
            $streaming='true';
        }
        if(in_array('mat_exam',$material)){
            $exams='true';
        }
        try {          
            $id = DB::table('courses')->insertGetId(
                ['name' => $request->name_course,
                'end_date'=>$f_fin.' '.$h_fin,
                'start_date'=>$f_inicio.' '.$h_inicio,
                'temary'=>$request->temario,
                'created_at'=>date("Y-m-d H:i:s"),
                'streaming'=>$streaming,
                'exams'=>$exams,
                'status'=>$request->estatus_curso
                ]
            );
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
        if(is_int($id)){
            $insert=$this->insertRelationshipUsers($profesores,$id);
            if($insert){
                 return $this->returnOper(true);
            }
        }
    }

    /**
     * [updateCourse description]
     * @param  [type] $request [description]
     * @param  [type] $id      [description]
     * @return [type]          [description]
     */
    function updateCourse($request,$id){
        $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->h_inicio))); 
        $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->h_fin))); 
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_inicio))); 
        $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_fin))); 
        $material=$request->material;
        $profesores=array();
        array_push($profesores,$request->profesor);
        $streaming='false';
        $exams='false';
        if(in_array('mat_clvivo',$material)){
            $streaming='true';
        }
        if(in_array('mat_exam',$material)){
            $exams='true';
        }
        try {   
            DB::table('courses')
                ->where('id',$id)
                ->update(
                    ['name' => $request->name_course,
                    'end_date'=>$f_fin.' '.$h_fin,
                    'start_date'=>$f_inicio.' '.$h_inicio,
                    'temary'=>$request->temario,
                    'updated_at'=>date("Y-m-d H:i:s"),
                    'streaming'=>$streaming,
                    'exams'=>$exams,
                    'status'=>$request->estatus_curso
                    ]
                );       
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }

        $data_teachers=array();
        $teachers=$this->relationshipusers($id,'T');
        foreach ($teachers as $key => $value) {
           $data_teachers[]=$value->user_id;
        }
        $dif=array_diff($data_teachers,$profesores);
        if(!empty($dif)){
            $delrel=$this->deleteRelationshipUsers($id);
            if($delrel){
                $insert=$this->insertRelationshipUsers($profesores,$id);
                if($insert){
                     return $this->returnOper(true);
                }
            }
        }else{
             return $this->returnOper(true);
        }
    }

    /**
     * [returnOper function que retorna la operacion insert,update,delete 
     * de la clase]
     * @param  [type] $oper      [true=operacion exitosa, false=error]
     * @param  [type] $cod_error [cod del error]
     * @return [json]         
     */
    function returnOper($bool,$cod_error=null){
        $oper=array();
        $oper["oper"]=$bool;
        if(!empty($cod_error)){
             $oper["error"]='COD: '.$cod_error;
        }
        return $oper;  
    }


    /**
     * [getCourse retorna todos los cursos ]
     * @param  [int] $id [id del curso]
     * @return [object]  
     */
    function getCourse($id=null,$active=false){
        $query=DB::table('courses');
        if(!empty($id))
            $query->where('courses.id',$id); 
        if($active==true)
                $query->where('courses.status','true'); 
        $query->select('courses.id','courses.name','courses.start_date', 'courses.end_date','courses.status','courses.temary','courses.exams','courses.streaming');
        $data=$query->get();      
        return $data; 
    }

    /**
     * [relationshipusers description]
     * @param  [type] $id    [description]
     * @param  [type] $sigla [description]
     * @param  [type] $user  [description]
     * @return [type]        [description]
     */
    function relationshipusers($id,$sigla,$user=null){
        $query=DB::table('courses_users');
        if(!empty($user))
             $query->where('courses_users.user_id',$user); 

        $query->where('courses_users.type',$sigla); 
        $query->where('courses_users.course_id',$id); 
        $query->join('users', 'users.id', '=', 'courses_users.user_id');
        $query->select('courses_users.user_id','courses_users.status','users.name','users.lastname','users.email');
        $data=$query->get();      
        return $data; 
    }


    /**
     * [deleteRelationshipUsers description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function deleteRelationshipUsers($id){

        $query->where('courses_users.course_id',$id); 
        $query->where('courses_users.type','T'); 
        $query=DB::table('courses_users');
        $delete=$query->delete();
        return $delete;
    }


    /**
     * [insertRelationshipUsers description]
     * @param  [type] $usuarios [description]
     * @param  [type] $id       [description]
     * @param  [type] $type     [description]
     * @param  [type] $status   [description]
     * @return [type]           [description]
     */
    function insertRelationshipUsers($usuarios,$id,$type=null,$status=null){
        if(empty($status))
            $status='true';

        if(empty($type))
            $type='T';

        try {
            foreach ($usuarios as $key=>$value) {
               $insert=DB::table('courses_users')->insert([
                         'user_id'=>$value,'course_id'=>$id,'type'=>$type,'status'=>$status]);
            }
            return true;
        } catch (Exception $e) {
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]);
        }
    }

    /**
     * [getIdRelationshipUsers description]
     * @param  [type] $course [description]
     * @param  [type] $user   [description]
     * @return [type]         [description]
     */
    function getIdRelationshipUsers($course,$user){
        $query=DB::table('courses_users');
        $query->where('courses_users.course_id',$course); 
        $query->where('courses_users.user_id',$user); 
        $query->select('courses_users.id');
        $data=$query->get(); 
        return $data;
    }

    /**
     * [updateRelationshipUsers description]
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function updateRelationshipUsers($id,$data){
         try {          
            $update=DB::table('courses_users');
            $update->where('id',$id);
            foreach ($data as $key => $value) {
                $update->update([$key=>$value]);
            }
            return $this->returnOper(true);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [deleteCourse description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function deleteCourse($id){
        try {          
            $update=DB::table('courses')->where('id', '=',$id)->delete();
            Log::info('Course ID: '.$id.' eliminado por: '.Session::get('email'));
            return $this->returnOper(true);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [listCoursesStreaming description]
     * @return [type] [description]
     */
    function listCoursesStreaming($user_id=null){
        $query=DB::table('courses');
        if(!empty($user_id))
            $query->where('courses_users.user_id',$user_id); 
        $query->where('courses.streaming','true');
        $query->where('courses.status','true'); 
        $query->join('courses_users', 'courses.id', '=', 'courses_users.course_id');
        $query->select('courses.id','courses.name');        
        $data=$query->get()->groupBy('courses.id');      
        return $data;
    }

    /**
     * [listCoursesExams lista de cursos habilitados para crear 
     * examenes]
     * @param  [int] $user_id [usuario de session]
     * @return [type]          [description]
     */
    function listCoursesExams($user_id=null){
        $query=DB::table('courses');
        if(!empty($user_id))
            $query->where('courses_users.user_id',$user_id); 
        $query->where('courses.exams','true');
        $query->where('courses.status','true'); 
        $query->join('courses_users', 'courses.id', '=', 'courses_users.course_id');
        $query->select('courses.id','courses.name');        
        $data=$query->get();      
        return $data;
    }

    /**
     * [listCoursesTeacher description]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    function listCoursesTeacher($user_id){
        $query=DB::table('courses');
        $query->where('courses_users.user_id',$user_id); 
        $query->where('courses.status','true'); 
        $query->join('courses_users', 'courses.id', '=', 'courses_users.course_id');
        $query->select('courses.id','courses.name');        
        $data=$query->get()->groupBy('courses.id');      
        return $data;
    }

    /**
     * [listCoursesStudent consulta de cursos inscritos por el estudiante]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    function listCoursesStudent($user_id){
        $query=DB::table('courses');
        $query->where('courses_users.user_id',$user_id); 
        $query->where('courses_users.status','true'); 
        $query->join('courses_users', 'courses.id', '=', 'courses_users.course_id');
        $query->select('courses.id','courses.name','courses.start_date','courses.end_date');        
        $data=$query->get()->groupBy('courses.id');      
        return $data;
    }

    /**
     * [listCourseCertific description]
     * @return [type] [description]
     */
    public function listCourseCertificates(){
        $query=DB::table('exams');
        $query->where('exams.type','f'); 
        $query->where('exams.status','F'); 
        //$query->where('exam_answers.qualification','A'); 
        $query->join('courses', 'courses.id', '=', 'exams.course_id');
        //$query->join('exam_answers', 'exam_answers.exam_id', '=', 'exams.id');
        $query->select('courses.id','courses.name');
        $data=$query->get()->groupBy('courses.id'); 
        return $data;
    }


}
