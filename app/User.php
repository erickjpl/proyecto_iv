<?php

namespace App;

use App\Profile;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;
use Session;

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
    public function getUsers($id=null,$profiles=array(),$email=null){

        $query=DB::table('users');

        if(!empty($id))
            $query->where('users.id',$id); 

        if(!empty($email))
            $query->where('users.email',$email); 

        if(!empty($profiles)) 
            $query->whereIn('users_profiles.profile_id',$profiles);     

        $query->join('users_profiles', 'users.id', '=', 'users_profiles.user_id');
        $query->select('users.id','users.name', 'users.email', 'users.lastname','users.active','users_profiles.profile_id');
        $data=$query->get();      
        return $data; 
    }

    /**
     * [saveUser registrar usuario]
     * @param  [type] $data [objeto user]
     * @return [json]       [fn. returnOper]
     */
    function saveUser($data){
        $id='';
        $oper=array();
        try {          
            $id = DB::table('users')->insertGetId(
                ['name' => $data->nombre,
                'lastname'=>$data->apellido,
                'active'=>'false',
                'email'=>$data->email,
                'created_at'=>date("Y-m-d H:i:s")
                ]
            );
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
        if(is_int($id)){
            $oper=array();
            try {          
                $insert=DB::table('users_profiles')->insert(
                    ['profile_id' => $data->perfil, 'user_id' => $id]
                );
                return $this->returnOper($insert);
            } catch(\Illuminate\Database\QueryException $ex){ 
                Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
                return $this->returnOper(false,$ex->errorInfo[0]);
            }
        }
    }

    /**
     * [returnOper function que retorna la operacion insert,update,delete 
     * de la clase]
     * @param  [type] $oper      [true=operacion exitosa, false=error]
     * @param  [type] $cod_error [cod del error]
     * @return [json]         
     */
    function returnOper($bool,$cod_error=null){
        $oper["oper"]=$bool;
        if(!empty($cod_error)){
             $oper["error"]='COD: '.$cod_error;
        }
        return response()->json($oper);  
    }

    /**
     * [updateUser funcion que updatea los usuarios]
     * @param  [object] $data [$request]
     * @param  [int] $id   [id del usuario]
     * @return [json]       [fn.returnOper ]
     */
    function updateUser($data,$id){
        try {          
            $update=DB::table('users');
            $update->where('id',$id);
            foreach ($data as $key => $value) {
                $update->update([$key=>$value]);
            }
            return $this->returnOper($update);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }

    /**
     * [deleteUser funcion que elimina usuarios]
     * @param  [int] $id [id del usuarios]
     * @return [json]     [fn.returnOper ]
     */
    function deleteUser($id){
        try {          
            $update=DB::table('users')->where('id', '=',$id)->delete();
            Log::info('Usuario ID: '.$id.' eliminado por: '.Session::get('email'));
            return $this->returnOper($update);
        } catch(\Illuminate\Database\QueryException $ex){         
            Log::error('COD: '.$ex->errorInfo[0].' ERROR: '.$ex->errorInfo[2].' LINE: '.$ex->getLine().' FILE: '.$ex->getFile());
            return $this->returnOper(false,$ex->errorInfo[0]); 
        }
    }
}
