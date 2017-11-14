<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\Course;
use App\Answer;
use Log;

class EvaluationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('evaluations.listeva');
    }

    function listEvaluations($course,$exam){
        $objCourse=new Course();
        $objAnswer= new Answer();
        $resp=array();
        $course=base64_decode($course);
        $exam=base64_decode($exam);
        if($course!='a' && $exam!='b'){
            $users=$objCourse->relationshipusers($course,'S');
            foreach ($users as $key => $value) {
               $data=array();
               $user_id=$value->user_id;
               $data["user_id"]=$user_id; 
               $data["name"]=$value->name.' '.$value->lastname;       
               $data["email"]=$value->email;
               $data_exam=$objAnswer->dataAnswer($exam,$user_id,$course);
               if(!empty($data_exam)){
                   if($data_exam[0]->qualification=='A'){
                        $qualification='<i title="Aprobado" class="glyphicon glyphicon-ok aprobexam"></i>';
                   }else if($data_exam[0]->qualification=='R'){
                        $qualification='<i title="Reprobado" class="glyphicon glyphicon-remove rechazexam"></i>';
                   }else{
                        $qualification='<button type="button" class="btn btn-primary acc-eva" title="Evaluar" data-user="'.$user_id.'" data-answer="'.$data_exam[0]->id.'"><i class="glyphicon glyphicon-list-alt"></i></button>';
                   }    
                }
               $data["qualification"]=$qualification;
               $data["exam"]=(!empty($data_exam[0]))?$data_exam[0]:array();
               $resp[]=$data;
            }
        }
        $result['data']=$resp;
        return response()->json($result);
    }


    function getExamAnswer($answer){
        $objAnswer= new Answer();
        $answer=base64_decode($answer);
        $respuestas=$objAnswer->getAnswersRel($answer);
        return response()->json($respuestas);
    }


    /**
     * [saveEvaluation description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveEvaluation(Request $request)
    {
        $objAnswer=new Answer();
        $idanswer=$request->answer_id;
        $data=array();
        $data["qualification"]=$request->resultado;
        $data["updated_at"]=date("Y-m-d H:i:s");
        if(!empty($request->coemntarios)){
            $data["coment"]=$request->coemntarios;
        }
        $update=$objAnswer->updateAnswer($data,$idanswer);
        return response()->json($update);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
