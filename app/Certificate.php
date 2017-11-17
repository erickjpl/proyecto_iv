<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Certificate extends Model
{	
	/**
	 * [insertCertificates description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
    function insertCertificates($data){
    	try {          
             $query = DB::table('certificates')->insert(
                ['user_id' => $data["user_id"],
                'created_at'=>$data["created_at"],
                'course_id'=>$data["course_id"],
                'created_at'=>date("Y-m-d H:i:s"),
                'certificate'=>$data["certificate"],
                ]
            );
            return $query;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [returnOper description]
     * @param  [type] $bool      [description]
     * @param  [type] $cod_error [description]
     * @return [type]            [description]
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
     * [validCertificate description]
     * @param  [type] $user   [description]
     * @param  [type] $course [description]
     * @return [type]         [description]
     */
    function validCertificate($user,$course){
        $query=DB::table('certificates');
        $query->where('certificates.user_id',$user); 
        $query->where('certificates.course_id',$course);
        $query->where('certificates.certificate','true');
        $data=$query->select('*')->get();
        if(count($data)>0){
            return true;
        }else{
            return false;
        }
    }

}
