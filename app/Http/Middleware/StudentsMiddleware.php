<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use \App\Profile;

class StudentsMiddleware
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
        $profile= new Profile();
        $est=$profile->pro_student;
        if($request->session()->get('profile_id')==$est){
            return $next($request);
        }else{
            \App::abort(403);
        }
    }
}
