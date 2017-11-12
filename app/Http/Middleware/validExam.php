<?php

namespace App\Http\Middleware;

use Closure;
use App\Exam;
use Session;
use App\Answer;

class validExam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $objExam=new Exam();
        $objAnswer=new Answer();
        $id_exam=base64_decode($request->idexam);
        $data_exam=$objExam::find($id_exam);
        $f_final=$data_exam->end_date;
        date_default_timezone_set('America/Caracas');
        $hoy=date('Y-m-d H:i:s');
        $user=Session::get('id');
        $validExam=$objAnswer->validExam($data_exam->course_id,$id_exam,$user);
        
        if($hoy>=$f_final || $data_exam->status=='F' || $validExam==false ){
            \App::abort(403);
        }else{
            return $next($request);
        }
        
    }
}
