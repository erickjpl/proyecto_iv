<?php

namespace App;

use App\Profile;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * [getUsers Lista los usuarios del sistema]
     * @param  [int] $id       [id del usuario]
     * @param  array  $profiles [perfiles del usuario]
     * @param  [string]  $email [email del usuario]
     * @return [object]      
     */
    public function getUsers($id=null,$profiles=array(),$email=''){

        $query=DB::table('users');

        if(!empty($id))
            $query->where('users.id',$id); 

        if(!empty($email))
            $query->where('users.email',$email); 

        if(!empty($profiles)) 
            $query->whereIn('users_profiles.profile_id',$profiles);     

        $query->join('users_profiles', 'users.id', '=', 'users_profiles.user_id');
        $query->select('users.id','users.name', 'users.email', 'users.lastname','users.active');
        $data=$query->get();      
        return $data; 

    }
}
