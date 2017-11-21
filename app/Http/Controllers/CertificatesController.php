<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View; 
use App\Course;
use App\Answer;
use App\Certificate;
use App\User;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendMail;
use Session;


class CertificatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('certificates.list_exams');
    }


    public function listCourses(){
        $objCourse=new Course();
        $data=$objCourse->listCourseCertificates();
        if(count($data)>0){
            $data=$data[""];
        }
        return response()->json($data);
    }


    public function listStudents($course){
       $objAnswer=new Answer();
       $course=base64_decode($course);
       $data["data"]='';
       if($course!='a'){
          $list_students=$objAnswer->listStudentCertif($course);
          if(count($list_students)>0){
                foreach ($list_students as $value) {
                foreach ($value as $val) {
                    $val->name=$val->name.' '.$val->lastname;
                     $val->actions='<div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-warning ">
                            <i class="glyphicon glyphicon-saved"></i>&nbsp;
                            <input name="users[]" class="select-users" value="'.$val->id.'" type="checkbox" autocomplete="off" >
                            <span class="glyphicon glyphicon-ok"></span>
                        </label>
                     </div>';
                    $val->qualification='<i title="Aprobado" class="glyphicon glyphicon-ok aprobexam"></i>';
                }              
              }
              $data["data"]=$list_students[""];
          }
       }
       return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objUser=new User();
        $objCertif=new Certificate();
        $objCourse= new Course();
        $course=base64_decode($request->course);
        $data_course=$objCourse::find($course);
        $users=explode(',',$request->users);

        if(is_array($users)){
            foreach ($users as $key => $value) {
                $data=array();
                $user=base64_decode($value);
                $data_user=$objUser::find($user);
                $name=$data_user->name.' '.$data_user->lastname;
                $mail=$data_user->email;
                $data["user_id"]=$user;
                $data["course_id"]=$course;
                $data["certificate"]='true';
                $data["created_at"]=date("Y-m-d H:i:s");
                $insert=$objCertif->insertCertificates($data);
                if($insert==true){
                    try {
                    $data_mail["name_course"]=strtoupper($data_course->name);
                    $data_mail["name_user"]=$name;
                     Mail::to($mail,$name)->send(new SendMail($data_mail,'msg_certificate'));
                     $insert=$objCourse->returnOper(true);
                    }catch (Exception $e) {
                        Log::error('COD: Falla Mail LINE: '.$ex->getLine().' FILE: '.$ex->getFile()); 
                        $error['cod']='Falla Mail';
                        $error['oper']=false;
                        return response()->json($error);
                    }     
                }else{
                    return response()->json($insert);
                }
            }
            return $objCertif->returnOper(true); 
        }else{
            return $objCertif->returnOper(false,'Error en el tipo de Usuario a ingresar'); 
        }        
    }

    /**
     * [donwloadCertifi description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function donwloadCertifi($course){
        $objCertif=new Certificate();
        $objUser=new User();
        $objCourse=new Course();
        $user=Session::get('id');
        $course=base64_decode($course);
        $dataUser=$objUser::find($user);
        $dataCourse=$objCourse::find($course);
        $datacertf=$objCertif->validCertificate($user,$course);
        if($datacertf==true){
            $name_student = $dataUser->name.' '.$dataUser->lastname;
            $di_student = $dataUser->identification_document;
            $course = strtoupper($dataCourse->name);
            header('Content-type: image/jpeg');
            $jpg_image = imagecreatefromjpeg('certif/certf.jpg');
            $black = imagecolorallocate($jpg_image, 0, 0, 0);
            $helvetica = 'certif/fuente/CALIFB.TTF';
            $image_width = imagesx($jpg_image);
            $image_height = imagesy($jpg_image);
            $text = $name_student." D.I. ".$di_student." a completado ".PHP_EOL." satisfactoriamente el Curso: ".$course;
            $text_box = @imagettfbbox(40,45,$helvetica,$text);
            $text_width = abs($text_box[2]-$text_box[0]);
            $text_height = abs($text_box[1]-$text_box[0]);
            $center = ($image_width-$text_width)/2;
            $centery = (($image_height-$text_height)/2)+80 ;
            imagettftext($jpg_image, 80, 0, $center/2, $centery, $black, $helvetica ,$text);
            imagejpeg($jpg_image);
            imagedestroy($jpg_image);
        }else{
            \App::abort(403);
        }
    }

}
