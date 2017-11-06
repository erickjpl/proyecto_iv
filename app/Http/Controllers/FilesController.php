<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Session;
use Storage;
use App\FileManager;
use Log;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user=Session::get('id');
         $user=base64_encode($user);
         return View::make('files.manager_files')->with(['user'=>$user]);
    }

    /**
     * [saveFiles funcion que guarda los archivos relacionados a un curso]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveFiles(Request $request)
    {   
         
        $objManager= new FileManager();
        $user=Session::get('id');
        $curso_id=base64_decode($request->curso);
        $request['user']=$user;
        $request['curso']=$curso_id;
        $img=$request->file('input-fa');
        $carpeta='curso_'.$curso_id.'_'.$user.'/'; 
        //dd($request);
        try {         
            $id=$objManager->saveFile($request);
            if(is_int($id)){              
                foreach ($img as $value){
                    $name_file=ltrim(time().'_'.$value->getClientOriginalName());
                    if(\Storage::disk('materialCursos')->put($carpeta.'/'.$name_file,\File::get($value))){
                        $arr=array();
                        $arr["id_file"]=$id;
                        $arr["name"]=$name_file;
                        $insert=$objManager->saveDetailfile($arr);
                        if($insert!=true){
                            return response()->json($objManager->returnOper($insert));
                        }
                    }
                }
                return response()->json(true);
            }else{
                return response()->json($id);   
            }
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return response()->json($objManager->returnOper(false,$ex->errorInfo[0]));
        }
    }


    /**
     * [deleteFiles description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function deleteFile(Request $request){
        $objManager= new FileManager();
        $curso_id=base64_decode($request->curso);
        $idfile=$request->key;
        $data=$objManager->getFilesDetails($curso_id,$idfile);
        $arr=array();
        if(count($data)>0){
            foreach ($data as $value) {
                $d["name"]=$value->name;
                $d["id"]=$value->id;
                $arr[]=$d;
            }
            if(!empty($arr)){
                return $this->deleteFileManager($arr,$curso_id);
            }
        }else{
          return response()->json($objManager->returnOper(false,'No hay archivos en la BD para eliminar'));  
        }
    }

    /**
     * [deletFileManager funcion que elimina los cursos de la carpeta public
     * y de la base de datos]
     * @param  [type] $arr      [description]
     * @param  [type] $curso_id [description]
     * @return [type]           [description]
     */
    function deleteFileManager($arr,$curso_id){
        $objManager= new FileManager();
        $user=Session::get('id');
        $carpeta='curso_'.$curso_id.'_'.$user.'/'; 
        try {  
            foreach ($arr as $value) {
                $delete=Storage::disk('materialCursos')->delete($carpeta.$value["name"]);
                if($delete==true){
                    $deletebd=$objManager->deleteFileDetail($value["id"]);
                    if($deletebd!=true){
                        return $deletebd;
                    }
                }else{
                  return response()->json($objManager->returnOper(false,'No hay archivos en la BD para eliminar'));  
                }
            }
                return response()->json(true);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return response()->json($objManager->returnOper(false,$ex->errorInfo[0]));
        }
    }

    /**
     * [getFiles description]
     * @return [type] [description]
     */
    function getFiles($id){
        $objManager= new FileManager();
        $curso_id=base64_decode($id);
        $data=$objManager->getFilesDetails($curso_id);
        return response()->json($data);
    }

}
