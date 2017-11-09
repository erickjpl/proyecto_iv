<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Exam extends Model
{	

	/**
	 * [insertExam description]
	 * @param  [type] $request [description]
	 * @return [type]          [description]
	 */
    public function insertExam($request){
    	try {          
            $query = DB::table('exams')->insertGetId(
                ['type' => $request["type"],
                'start_date'=>$request["start_date"],
                'end_date'=>$request["end_date"],
                'created_at'=>date("Y-m-d H:i:s"),
                'course_id'=>$request["course"],   
                ]
            );
            return $query;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [insertQuestion description]
     * @param  [type] $request [description]
     * @param  [type] $idexam  [description]
     * @return [type]          [description]
     */
    public function insertQuestion($request,$idexam){
    	try {          
            $query = DB::table('questions')->insert(
                ['description' => $request["description"],
                'exam_id'=>$idexam,
                'type'=>$request["type"],
                'options'=>$request["options"],
                ]
            );
            return true; 
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
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
     * [lisCoursesUser lista de cursos por usuario]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    function lisCoursesUser($user_id){
        $query=DB::table('exams');
        $query->where('courses_users.type','T'); 
        $query->where('courses_users.user_id',$user_id); 
        $query->join('courses_users', 'exams.course_id', '=', 'courses_users.course_id');
        $query->join('courses', 'courses.id', '=', 'courses_users.course_id');
        $query->select('exams.id','exams.type','exams.start_date','exams.end_date','exams.status','exams.course_id','courses.name');
        $data=$query->get()->groupBy('exams.id');   
        return $data;
    }
}
