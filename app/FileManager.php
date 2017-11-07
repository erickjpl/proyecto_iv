<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class FileManager extends Model
{   

    /**
     * [saveFile funcion que guarda la descripcion de todos los archivos 
     * relacionados la curso]
     * @param  [object] $request [datos formulario]
     * @return [int]          [id del registro]
     */
    function saveFile($request){
        try {          
            $id = DB::table('files_manager')->insertGetId(
                ['description' => $request->descripcion,
                'created_at'=>date("Y-m-d H:i:s"),
                'course_id'=>$request->curso,
                'user_id'=>$request->user,
                ]
            );
            return $id;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [saveDetailfile function que guarda los detalles de los archivos]
     * @param  [array] $request []
     * @return [bool]          []
     */
    function saveDetailfile($request){

        try {          
            $insert=DB::table('files')->insert([
                         'file_manager_id'=>$request["id_file"],'name'=>$request["name"]]);
            return true;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [deleteDetailfile function que elimina los archivos multimedia de la bd]
     * @param  [int] $idfile [id relacional]
     * @return [bool]         []
     */
    function deleteDetailfile($idfile){
        try {          
            $update=DB::table('files')->where('file_manager_id', '=',$idfile)->delete();
            return $this->returnOper(true);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [getFilesDetails description]
     * @param  [type] $curso_id [description]
     * @return [type]           [description]
     */
    function getFilesDetails($curso_id,$file_id=null){

        $query=DB::table('files');
        if(!empty($file_id)){
            $query->where('files.id',$file_id); 
        }
        $query->where('files_manager.course_id',$curso_id); 
        $query->join('files_manager', 'files_manager.id', '=', 'files.file_manager_id');
        $data=$query->select('files.id','files.name','files_manager.description')->get();
        return $data;
    }

    /**
     * [deleteFileDetail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function deleteFileDetail($id){
        try {          
            $delete=DB::table('files')->where('id', '=',$id)->delete();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }


    /**
     * [getFilesManager funcion que retorna el detalle general de los archivos relacionados
     * en files]
     * @param  [type] $curso_id [description]
     * @return [type]           [description]
     */
    function getFilesManager($curso_id){

        $query=DB::table('files_manager');
        $query->where('files_manager.course_id',$curso_id); 
        $data=$query->select('files_manager.description','files_manager.id')->get();
        return $data;
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
