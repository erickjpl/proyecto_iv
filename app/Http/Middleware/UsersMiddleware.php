<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use \App\Profile;

class UsersMiddleware
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
        $adm=$profile->pro_admin;
        if($request->session()->get('profile_id')==$adm){
            return $next($request);
        }else{
            \App::abort(404);
        }
    }
}
