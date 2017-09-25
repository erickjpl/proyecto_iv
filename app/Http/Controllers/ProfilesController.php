<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;

class ProfilesController extends Controller
{
    
    /**
     * [getProfiles consulta los tipos de perfiles de la aplicacion]
     * @return [json] 
     */
    public function getProfiles(){
    	$objProfile= new Profile();
    	$data=$objProfile->listProfiles();
    	return response()->json($data);
    }
}
