<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class Streaming extends Model
{	
    
    /**
     * [insetStreaming funcion que guarda el evento streaming]
     * @param  [type] $request [obj del formulario]
     * @return [type]          [description]
     */
    public function insetStreaming($request){
    	try {          
            $query = DB::table('streamings')->insert(
                ['url' => $request["url"],
                'start_date'=>$request["fecha_inicio"],
                'description'=>$request["descripcion"],
                'user_id'=>$request["user_id"],
                'course_id'=>$request["course"],
                'created_at'=>date("Y-m-d H:i:s"),   
                ]
            );
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


    function getStreamings($id=null,$user_id=null,$status=''){
    	 $query=DB::table('streamings');
        if(!empty($id))
            $query->where('streamings.id',$id);

         if(!empty($user_id))
            $query->where('streamings.user_id',$user_id); 

         if(!empty($status))
            $query->where('streamings.status',$status); 

        $query->join('courses', 'courses.id', '=', 'streamings.course_id');
        $query->join('users', 'users.id', '=', 'streamings.user_id');
        $query->select('streamings.id','streamings.url','streamings.start_date', 'streamings.description','courses.name','users.name as name_user','users.lastname','streamings.course_id');
        $data=$query->get();      
        return $data; 
    }
}
