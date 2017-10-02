<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Occupation;

class OccupationController extends Controller
{
    
    /**
     * [getOcupations description]
     * @return [type] [description]
     */
    public function getOcupations(){
    	$objOccupation= new Occupation();
    	$data=$objOccupation->listOcupations();
    	return response()->json($data);
    }
}
