<?php

namespace App\Http\Middleware;

use Closure;
use App\Exam;

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
        $id_exam=base64_decode($request->idexam);
        $data_exam=$objExam::find($id_exam);
        $f_inicio=$data_exam->start_date;
        $f_final=$data_exam->end_date;
        date_default_timezone_set('America/Caracas');
        $hoy=date('Y-m-d H:i:s');
        if($hoy>=$f_final || $data_exam->status=='F' ){
            \App::abort(403);
        }else{
            return $next($request);
        }
        
    }
}
