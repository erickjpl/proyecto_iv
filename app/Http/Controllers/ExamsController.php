<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\Exam;
use App\Course;
use App\Answer;
use Session;
use Log;

class ExamsController extends Controller
{
   
    /**
     * [index vista para visualizar modulo de examenes]
     * @return [type] [description]
     */
    public function index()
    {
        return View::make('exams.listexams');
    }

    
    /**
     * [create vista de crear examen]
     * @return [type] [description]
     */
    public function create()
    {
        return View::make('exams.create');
    }

    
    /**
     * [store funcion que guarda  las preguntas del examen (data de examen)]
     * @param  Request $request [obj del formulario]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $objExam=new Exam();
        //valid tipo de curso
        if($request->type_exam=='f')
            $validCreate=$objExam->validTypeExam($request->course);
        else
            $validCreate=true;

        if($validCreate==true){
            $user=Session::get('id');
            $arr_simples=$request->question; //preguntas simples
            $arr_multiples=array(); //preguntas multiples
            foreach ($request->all() as $key => $value) {
                $data=array();
                $pos = strstr($key,'question-');
                if(!empty($pos)){
                    $data["question"]=$value[0];
                    $keypregunta=explode('-',$key);
                    $idopcion=$keypregunta[1];
                    $v='option-'.$idopcion;
                    $data["opciones"]=$request->$v;
                    array_push($arr_multiples,$data);
                }
            }
            $insert=array();
            $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->hora_inicio))); 
            $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->hora_final))); 
            $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_inicio))); 
            $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_fin)));        
            $insert["start_date"]=$f_inicio.' '.$h_inicio;
            $insert["end_date"]=$f_fin.' '.$h_fin;
            $insert["course"]=$request->course;
            $insert["type"]=$request->type_exam;
            $insert["user_id"]=$user;
            $id_exam=$objExam->insertExam($insert);
            return $this->insertExam($request,$id_exam);
        }else{
            return $objExam->returnOper(false,'Solo se permite un solo examen Final por curso'); 
        }
            
    }

    /**
     * [insertExam description]
     * @param  [type] $request [description]
     * @param  [type] $id_exam [description]
     * @return [type]          [description]
     */
    function insertExam($request,$id_exam){
        $objExam=new Exam();
        $arr_simples=$request->question; //preguntas simples
        $arr_multiples=array(); //preguntas multiples
        foreach ($request->all() as $key => $value) {
            $data=array();
            $pos = strstr($key,'question-');
            if(!empty($pos)){
                $data["question"]=$value[0];
                $keypregunta=explode('-',$key);
                $idopcion=$keypregunta[1];
                $v='option-'.$idopcion;
                $data["opciones"]=$request->$v;
                array_push($arr_multiples,$data);
            }
        }
        if(is_int($id_exam)){
            if(!empty($arr_simples)){
                foreach ($arr_simples as $value) {
                   $option=array();
                   $option["description"]=$value;
                   $option["type"]='c';
                   $option["options"]=null;
                   $insert=$objExam->insertQuestion($option,$id_exam);
                   if($insert!=true){
                        return response()->json($insert);
                    }
                }
            }
            if(!empty($arr_multiples)){
                foreach ($arr_multiples as $key => $value) {
                    $option=array();
                    $option["description"]=$value["question"];
                    $option["type"]='o';
                    $option["options"]=implode(',',$value["opciones"]);
                    $insert=$objExam->insertQuestion($option,$id_exam);
                    if($insert!=true){
                        return response()->json($insert);
                    }
                }
            }
            return $objExam->returnOper(true); 
        }else{
            return $objExam->returnOper(false,'Error al guardar examen'); 
        }
    }

    /**
     * [listExams consula examenes dataTables]
     * @return [json] []
     */
    function listExams(){
        $objExam=new Exam();
        $user=Session::get('id');
        $data=$objExam->lisCoursesUser($user);
        $resp["data"]=array();
        if(count($data)>0){
            foreach ($data as $key => $val) {
                foreach ($val as $k => $value) {
                     $id=base64_encode($value->id);
                     $estatus=$value->status;
                     switch ($value->status) {
                         case 'B':
                             $value->status='Borrador';
                         break;
                         case 'P':
                             $value->status='Activo para Presentar';
                         break;
                         case 'F':
                             $value->status='Finalizado';
                         break;
                     }
                     $value->start_date=date("d/m/Y h:i:s A",strtotime($value->start_date)); 
                     $value->end_date=date("d/m/Y h:i:s A",strtotime($value->end_date)); 
                     $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-exam="'.$id.'">Modificar</button>&nbsp;<button type="button" class="acc_status btn btn-warning btn-xs" data-exam="'.$id.'" data-estatus="'.$estatus.'" >Estatus Examen</button>&nbsp;<button type="button" class="acc_del btn btn-danger btn-xs" data-exam="'.$id.'">Eliminar</button>';

                    $value->actions=$actions;
                    $resp["data"][]=$value;
                }
            }
        }
        return response()->json($resp);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id_exam=base64_decode($id);
        $objExam=new Exam();
        $objExam=$objExam::find($id_exam);
        $this->authorize('view', $objExam);
        return View::make('exams.create',['exam' =>$id]);
    }

    /**
     * [consultExam description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function consultExam(Request $request){
        $objExam=new Exam();
        $id_exam=base64_decode($request->exam_id);
        $data_exam=$objExam->consultExam($id_exam);
        $result=array();
        if(count($data_exam)>0){
            $data_exam=$data_exam[0];
            $start_date=explode(' ',$data_exam->start_date);
            $end_date=explode(' ',$data_exam->end_date);
            $data_exam->start_date=date("d/m/Y",strtotime($start_date[0]));
            $data_exam->h_inicio=date("h:i:s A",strtotime($start_date[1])); 
            $data_exam->end_date=date("d/m/Y",strtotime($end_date[0])); 
            $data_exam->h_fin=date("h:i:s A",strtotime($end_date[1]));            
            $id=$data_exam->id;
            $data_questions=$objExam->consultOptions($id);
            $arr=array();
            foreach ($data_questions as $value) {
                $arr[]=$value;
            }
            $data_exam->questions=$arr;          
            $result=$data_exam;
        }
        return response()->json($result);
    }   

    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {   
        $objExam=new Exam();
        $exam_id=base64_decode($request->exam);

        //valid tipo de curso
        if($request->type_exam=='F')
            $validUpdate=$objExam->validTypeExam($request->course,$exam_id);
        else
            $validUpdate=true;

        if($validUpdate==true){
            $user=Session::get('id');
            $exam_id=(int) $exam_id;
            $delete=$objExam->delteOptions($exam_id);
            if($delete==true){
                $questions=$this->insertExam($request,$exam_id);
                if($questions["oper"]==true){
                    $update=array();
                    $h_inicio=date("H:i:s",strtotime(str_replace('/', '-',$request->hora_inicio))); 
                    $h_fin=date("H:i:s",strtotime(str_replace('/', '-',$request->hora_final))); 
                    $f_inicio=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_inicio))); 
                    $f_fin=date("Y-m-d",strtotime(str_replace('/', '-',$request->fecha_fin))); 
                    $update["start_date"]=$f_inicio.' '.$h_inicio;
                    $update["end_date"]=$f_fin.' '.$h_fin;
                    $update["course_id"]=$request->course;
                    $update["type"]=$request->type_exam;
                    $update["user_id"]=$user;
                    $update['updated_at']=date("Y-m-d H:i:s");
                    $update['status']=$request->status;
                    $updateexam=$objExam->updateExam($exam_id,$update);
                    return $updateexam;
                }
            }else{
                return $delete;
            }
        }else{
            return $objExam->returnOper(false,'Solo se permite un solo examen Final por curso');
        }
        
    }

    function setEstatus(Request $request){
        $id_exam=base64_decode($request->exam);
        $objExam=new Exam();
        $objAnswer=new Answer();
        try {
            $objExam=$objExam::find($id_exam);
            $objExam->status=$request->est;
            if($request->est=='B'){
                $valid=$objAnswer->validExam($objExam->course_id,$objExam->id);
                if($valid==true){
                    $objExam->update();
                    return $objExam->returnOper(true);
                }else{
                    return $objExam->returnOper(false,'No se puede cambiar de Estatus a Borrador. Ya se presentó el examen por un estudiante.');
                }
            }else{
                $objExam->update();
                return $objExam->returnOper(true);
            }
              
        } catch (Exception $e) {
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $objExam->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {   
       $arrexam=array('P','F');
       $objExam=new Exam(); 
       $objAnswer=new Answer(); 
       $id_exam=base64_decode($id); 
       try {
            $objExam=$objExam::find($id_exam);
            $valid=$objAnswer->validExam($objExam->course_id,$objExam->id);
            if(!in_array($objExam->status,$arrexam) && $valid==true){
                $objExam->delete();
                Log::info('Examen ID: '.$id_exam.' eliminado por: '.Session::get('email'));
                return $objExam->returnOper(true);
            }else{
                return $objExam->returnOper(false,'El examen debe estar en Estatus Borrador para poder eliminarlo'); 
            }
            
        } catch (Exception $e) {
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $objExam->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [listExamsStudent description]
     * @return [type] [description]
     */
    public function listExamsStudent(){
        $objExam= new Exam();
        $objAnswer=new Answer();
        $user=Session::get('id');
        $data_exam=$objExam->listExamStudent($user);
        if(count($data_exam)>0){
            $data_exam=$data_exam[""];
            foreach ($data_exam as $value) {
                $data_eva=$objAnswer->dataAnswer($value->id,$user,$value->course_id);
                $value->calificacion=array();
                if(!empty($data_eva[0]->qualification)){
                    $value->calificacion=$data_eva[0];
                }
                $validExam=$objAnswer->validExam($value->course_id,$value->id,$user);
                $value->examen_finalizado=$validExam;
                $value->start_date=date("d/m/Y h:i:s A",strtotime($value->start_date)); 
                $value->end_date=date("d/m/Y h:i:s A",strtotime($value->end_date));

            }
        }
        return response()->json($data_exam);
    }

    /**
     * [viewExamStudent description]
     * @param  [type] $id     [description]
     * @param  [type] $course [description]
     * @param  [type] $type   [description]
     * @return [type]         [description]
     */
    public function viewExamStudent($id,$course){
        $objExam=new Exam();
        $exam_id=base64_decode($id);
        $course=base64_decode($course);
        $data_exam=$objExam::find($exam_id);
        $type=$data_exam->type;
        $f_fin=date("d/m/Y h:i:s A",strtotime($data_exam->end_date));
        $type=($type=='P'?'Parcial':'Final');

        return View::make('exams.viewstudent',['exam'=>$exam_id,'namecourse'=>$course,'type'=>$type,'f_fin'=>$f_fin,'course_id'=>$data_exam->course_id]);
    }

    /**
     * [listQuestions description]
     * @return [type] [description]
     */
    public function listQuestions($exam_id){
        $objExam= new Exam();
        $exam_id=base64_decode($exam_id);
        $questions=$objExam->consultOptions($exam_id);
        return response()->json($questions);
    }

    public function listExamsEvaluations($course){
        $objExam= new Exam();
        $course=base64_decode($course);
        $exams=$objExam->listExams($course);
        return response()->json($exams);
    }


}
