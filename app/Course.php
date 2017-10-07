<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Course extends Model
{
    
    function saveCourse($request){
        $id="";
        $f_inicio=date("Y-m-d", strtotime($request->f_inicio));
        $h_inicio=date("H:m:s", strtotime($request->h_inicio));
        $f_fin=date("Y-m-d", strtotime($request->f_fin));
        $h_fin=date("H:m:s", strtotime($request->h_fin));
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
                'exams'=>$exams
                ]
            );
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
        if(is_int($id)){
            $oper=array();
            try {
                foreach ($profesores as $key=>$value) {
                   $insert=DB::table('courses_teachers')->insert([
                             'user_id'=>$value,'course_id'=>$id]);
                }
                return $this->returnOper(true);
            } catch (Exception $e) {
                Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
                return $this->returnOper(false,$ex->errorInfo[0]);
            }
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

}
