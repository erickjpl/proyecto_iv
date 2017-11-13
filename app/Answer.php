<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Answer extends Model
{   
    /**
     * [insertExam description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function insertExam($request){
        try {          
            $query = DB::table('exam_answers')->insertGetId(
                ['user_id' => $request["user_id"],
                'course_id'=>$request["course_id"],
                'exam_id'=>$request["exam_id"],
                'created_at'=>date("Y-m-d H:i:s"),   
                ]
            );
            return $query;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [insertAnswer description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function insertAnswer($request){
        try {          
            $query = DB::table('answers_rel')->insert(
                ['question' => $request["question"],
                'answer'=>$request["answer"],
                'exam_answer_id'=>$request["exam_answer_id"],
                'question_id'=>$request["question_id"],   
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
     * [validExam description]
     * @param  [type] $course  [description]
     * @param  [type] $exam_id [description]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    function validExam($course,$exam_id,$user_id=null){
        $query=DB::table('exam_answers');
        if(!empty($user_id)){
            $query->where('exam_answers.user_id',$user_id); 
        }
        $query->where('exam_answers.exam_id',$exam_id);
        $query->where('exam_answers.course_id',$course); 
        $query->select('exam_answers.id');
        $data=$query->get();
        if(count($data)>0){
            return false;
        }else{
            return true;
        }

    }
}
