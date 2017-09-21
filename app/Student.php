<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Profile;
use DB;

class Student extends Model
{
    
    /**
     * [getStudents retorna lista de todos los estudiantes]
     * @return [object] 
     */
	public function getStudents(){
		$objProfile= new Profile();
		$data = DB::table('users')
				 ->select('users.id','users.name', 'users.email', 'users.lastname','users.active')
				->join('users_profiles', 'users.id', '=', 'users_profiles.user_id')
                ->where('users_profiles.profile_id',$objProfile->pro_student)
                ->get();
        return $data;
	}

}
