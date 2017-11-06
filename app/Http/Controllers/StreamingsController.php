<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use App\Streaming;
class StreamingsController extends Controller
{
    
    /**
     * [index funcion que renderiza la vista del modulo de streaming]
     * @return [] []
     */
    public function index()
    {
        $user=Session::get('id');
        $user=base64_encode($user);
        return View::make('streamings.liststreamings')->with(['user'=>$user]);
    }

    
    /**
     * [saveEvent funcion que guarda el streaming]
     * @param  Request $request [objeto del formulario]
     * @return [array]           
     */
    public function saveEvent(Request $request)
    {   
        $objStreaming= new Streaming();
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_inicio))); 
        $h_inicio=date("H:i:s",strtotime($request->h_inicio)); 
        $request["fecha_inicio"]=$f_inicio.' '.$h_inicio;
        $request["course"]=base64_decode($request->course);
        $request["user_id"]=$request->session()->get('id');
        $save=$objStreaming->insetStreaming($request);
        return response()->json($save);
    }

   
     /**
      * [listStreamings function que renderiza la consulta dataTables Streaming]
      * @return [] []
      */
     public function listStreamings(){
        $objStreaming= new Streaming();
        $user=Session::get('id');
        $data=$objStreaming->getStreamings('',$user,'true');
        $resp["data"]=array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                 $id=base64_encode($value->id);
                 $start_date=explode(' ',$value->start_date);
                 $value->url='<a href="'.$value->url.'" target="blank">'.$value->url.'</a>';
                 $value->start_date=date("d/m/Y",strtotime($start_date[0])).' '.date("h:i:s A",strtotime($start_date[1])); 
                 $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-streaming="'.$id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-streaming="'.$id.'">Eliminar</button>&nbsp<button type="button" title="finalizar streaming" class="acc-inactivar btn btn-warning btn-xs" data-streaming="'.$id.'">Finalizar Evento</button>';

                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);
    }


    /**
     * [getStreaming function que consulta la data de un streaming]
     * @param  Request $request [objeto]
     * @return [json]           []
     */
    public function getStreaming(Request $request)
    {
       $objStreaming= new Streaming();
       $streamingid=base64_decode($request->streaming);
       $data=$objStreaming->getStreamings($streamingid);
       $time_ini=explode(' ',$data[0]->start_date);
       $h_inicio=date("h:i:sA",strtotime($time_ini[1])); 
       $f_inicio=date("d-m-Y",strtotime($time_ini[0]));
       $f_inicio=str_replace('-','/',$f_inicio);
       $data[0]->start_date=$f_inicio;
       $data[0]->h_inicio=$h_inicio;
       return response()->json($data);
    }

    /**
     * [updateStreaming funcion que modifica un streaming]
     * @param  Request $request [objeto del formulario]
     * @param  [int]  $id      [id del streaming]
     * @return [json]           []
     */
    public function updateStreaming(Request $request, $id)
    { 
        $objStreaming= new Streaming();
        $id=base64_decode($id);
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_inicio))); 
        $h_inicio=date("H:i:s",strtotime($request->h_inicio)); 
        $request["fecha_inicio"]=$f_inicio.' '.$h_inicio;
        $request["course"]=base64_decode($request->course);
        $udpate=$objStreaming->updateStreaming($request,$id);
        return response()->json($udpate);
    }

    /**
     * [deleteStreaming funcion que elimina un streaming]
     * @param  [int] $id [id del streaming]
     * @return [json]     []
     */
    public function deleteStreaming($id)
    {
        $objStreaming= new Streaming();
        $id=base64_decode($id);
        $delete=$objStreaming->deleteStreaming($id);
        return response()->json($delete);
    }

    /**
     * [finalizarEvento funcion que finaliza el evento streaming]
     * @param  [int] $id [id del streaming]
     * @return [json]     []
     */
    function finalizarEvento($id){
        
        try {
            $objStreaming= new Streaming();
            $id=base64_decode($id);
            $objStreaming=$objStreaming::find($id);
            $objStreaming->status='false';
            $objStreaming->update();
            return response()->json($objStreaming->returnOper(true));
        }catch (Exception $e) {
            Log::error('Falla en Finalizar Evento LINE: '.$ex->getLine().' FILE: '.$ex->getFile()); 
            return response()->json($objStreaming->returnOper(false,500));
        }   
    }

    /**
     * [listStreamingStudent description]
     * @return [type] [description]
     */
    function listStreamingStudent($course){
        $objStreaming= new Streaming();
        $user=Session::get('id');
        $course_id=base64_decode($course);
        $data=$objStreaming->listStreamingStudent($course_id,$user);
        if(count($data)>0){
            foreach ($data as $value) {
                $value->start_date=date("d/m/Y h:i:s A",strtotime($value->start_date));
            }
        }
        return response()->json($data);
        
    }
}
