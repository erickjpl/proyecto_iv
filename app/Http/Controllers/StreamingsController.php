<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use App\Streaming;
class StreamingsController extends Controller
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
        return View::make('streamings.liststreamings')->with(['user'=>$user]);
    }

    
    /**
     * [saveEvent description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveEvent(Request $request)
    {   
        $objStreaming= new Streaming();
        $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_inicio))); 
        $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->h_inicio))); 
        $request["fecha_inicio"]=$f_inicio.' '.$h_inicio;
        $request["course"]=base64_decode($request->course);
        $request["user_id"]=$request->session()->get('id');
        $save=$objStreaming->insetStreaming($request);
        return response()->json($save);
    }

   
     /**
      * [listStreamings description]
      * @return [type] [description]
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
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-streaming="'.$id.'">Eliminar</button>';

                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);
    }



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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
