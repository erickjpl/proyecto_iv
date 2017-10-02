<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{	

	/**
	 * [listOcupations description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function listOcupations($id=null){

   		$query=DB::table('occupations');
        if(!empty($id))
            $query->where('occupations.id',$id); 
        $query->select('occupations.id','occupations.name');
        $data=$query->get();      
        return $data;
   }
}
