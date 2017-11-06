<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Session;

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

    /**
     * [getStreamings Funcion que consulta los streamings]
     * @param  [int] $id      [id del streaming]
     * @param  [int] $user_id [id del usuario q cargo el streaming]
     * @param  string $status  [true=>streamings activos, false=>streamings inactivos]
     * @return [object]          []
     */
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

    /**
     * [updateStreaming funcion que modifica el streaming]
     * @param  [objeto] $data [datos para modificar]
     * @param  [int] $id   [id del streaming]
     * @return [array]       [fn returnOper]
     */
    function updateStreaming($data,$id){
        try {   
            DB::table('streamings')
                ->where('id',$id)
                ->update(
                    ['url' => $data->url,
                    'start_date'=>$data->fecha_inicio,
                    'course_id'=>$data->course,
                    'updated_at'=>date("Y-m-d H:i:s"),
                    ]
                );       
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [deleteStreaming funcion que elimina el stareaming]
     * @param  [int] $id [id del streaming]
     * @return [array]     [returnOper]
     */
    function deleteStreaming($id){
        try {          
            $delete=DB::table('streamings')->where('id', '=',$id)->delete();
            Log::info('Usuario ID: '.$id.' eliminado por: '.Session::get('email'));
            return $this->returnOper(true);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [listStreamingStudent description]
     * @param  [type] $courseid  [description]
     * @param  [type] $studentid [description]
     * @return [type]            [description]
     */
    function listStreamingStudent($courseid,$studentid){
        $query=DB::table('streamings');
        $query->where('courses_users.user_id',$studentid); 
        $query->join('courses_users', 'courses_users.course_id', '=', 'streamings.course_id');
        $query->join('courses', 'courses.id', '=', 'streamings.course_id');
        $query->select('streamings.id','streamings.url','streamings.start_date','courses.name',
        'streamings.status');
        $data=$query->get();      
        return $data; 
    }

}
