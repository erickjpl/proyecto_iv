<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Profile extends Model
{
   
   public $pro_student=1;
   public $pro_admin=2;
   public $pro_teacher=3;

   /**
    * [listProfiles funcion que retorna los tipos de perfiles de la aplicacion]
    * @param  [int] $id_profile [id del perfil]
    * @return [object]            
    */
   public function listProfiles($id_profile=null){

   		$query=DB::table('profiles');
        if(!empty($id_profile))
            $query->where('profiles.id',$id_profile); 
        $query->select('profiles.id','profiles.name','profiles.active_select');
        $data=$query->get();      
        return $data;

   }

}
