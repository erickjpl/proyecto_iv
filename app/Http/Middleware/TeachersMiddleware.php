<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use \App\Profile;

class TeachersMiddleware
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
        $est=$profile->pro_teacher;
        if($request->session()->get('profile_id')==$est){
            return $next($request);
        }else{
            \App::abort(403);
        }
    }
}
