<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Course extends Model
{


    function saveCourse($request){
        $id="";
        $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->h_inicio))); 
        $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->h_fin))); 
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_inicio))); 
        $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_fin))); 
        $material=explode(',',$request->material);
        $profesores=explode(',',$request->profesor);
        $streaming='false';
        $exams='false';
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
            $insert=$this->insertRelationshipTeacher($profesores,$id);
            if($insert){
                 return $this->returnOper(true);
            }
            /*try {
                foreach ($profesores as $key=>$value) {
                   $insert=DB::table('courses_teachers')->insert([
                             'user_id'=>$value,'course_id'=>$id]);
                }
                return $this->returnOper(true);
            } catch (Exception $e) {
                Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
                return $this->returnOper(false,$ex->errorInfo[0]);
            }*/
        }
    }


    function updateCourse($request,$id){
        $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->h_inicio))); 
        $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->h_fin))); 
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_inicio))); 
        $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->f_fin))); 
        $material=explode(',',$request->material);
        $profesores=explode(',',$request->profesor);
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
        $teachers=$this->relationshipteacher($id);
        foreach ($teachers as $key => $value) {
           $data_teachers[]=$value->user_id;
        }
        $dif=array_diff($data_teachers,$profesores);
        if(!empty($dif)){
            $delrel=$this->deleteRelationshipTeacher($id);
            if($delrel){
                $insert=$this->insertRelationshipTeacher($profesores,$id);
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
     * [relationshipteacher description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function relationshipteacher($id){
        $query=DB::table('courses_teachers');
        $query->where('courses_teachers.course_id',$id); 
        $query->select('courses_teachers.user_id');
        $data=$query->get();      
        return $data; 
    }


    /**
     * [deleteRelationshipTeacher description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function deleteRelationshipTeacher($id){
        $delete=DB::table('courses_teachers')->where('course_id',$id)->delete();
        return $delete;
    }


    /**
     * [insertRelationshipTeacher description]
     * @return [type] [description]
     */
    function insertRelationshipTeacher($profesores,$id){
        try {
            foreach ($profesores as $key=>$value) {
               $insert=DB::table('courses_teachers')->insert([
                         'user_id'=>$value,'course_id'=>$id]);
            }
            return true;
        } catch (Exception $e) {
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]);
        }
    }

}
