<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Answer;
use App\Exam;
use Session;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * [validInsertExam description]
     * @param  [type] $id_exam [description]
     * @return [type]          [description]
     */
    public function validInsertExam($id_exam){
        $objExam=new Exam();
        $data_exam=$objExam::find($id_exam);
        $f_inicio=$data_exam->start_date;
        $f_final=$data_exam->end_date;
        date_default_timezone_set('America/Caracas');
        $hoy=date('Y-m-d H:i:s');
        if($hoy>=$f_final || $data_exam->status=='F' ){
            return false;
        }else{
           return true;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $objExam=new Exam();
        $objAnswer=new Answer();
        $exam_id=base64_decode($request->exam_id);
        $course_id=base64_decode($request->course_id);
        $user=Session::get('id');
        
        $valid=$this->validInsertExam($exam_id);
        if($valid==true){
            $arr=array();
            $arr["user_id"]=$user;
            $arr["course_id"]=$course_id;
            $arr["exam_id"]=$exam_id;
            $id_insert=$objAnswer->insertExam($arr);
            if(is_int($id_insert)){
                foreach ($request->all() as $key=>$value) {
                    $data=array();
                    $pos = strstr($key,'answer_');
                    if(!empty($pos)){
                        $preguntaid=explode('_',$key);
                        $data["question_id"]=$preguntaid[1];
                        $pregunta=$objExam->consultOptions($exam_id,$preguntaid[1]);
                        $data["question"]=$pregunta[0]->description;
                        $data["exam_answer_id"]=$id_insert;
                        if(is_array($value)){
                           $data["answer"]=implode(',',$value); 
                        }else{
                            $data["answer"]=$value;
                        }
                        $insertAnswer=$objAnswer->insertAnswer($data);
                        if($insertAnswer!=true){
                            return $objAnswer->returnOper($insertAnswer);
                        }
                    }
                }
                return $objAnswer->returnOper(true);
            }else{
                return $objAnswer->returnOper($id_insert);
            }
        }else{
            return \App::abort(403);
        }
        
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
