<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\Exam;
use App\Course;
use Session;


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
                     $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-exam="'.$id.'">Modificar</button>
                              <button type="button" class="acc_del btn btn-danger btn-xs" data-exam="'.$id.'">Eliminar</button><button type="button" class="acc_status btn btn-warning btn-xs" data-exam="'.$id.'">Estatus Examen</button>';

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
        $user=Session::get('id');
        $exam_id=base64_decode($request->exam);
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
                $updateexam=$objExam->updateExam($exam_id,$update);
                return $updateexam;
            }
        }else{
            return $delete;
        }
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
