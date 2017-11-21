<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ValidAjaxController extends Controller
{
    
    public function valid($act,$value){
        $objUser=new User();
        switch ($act) {
            case 'validmail':
               $data=$objUser->getUsers('','',$value);
               if(count($data)>0){
                 return response()->json(false);
               }else{
                return response()->json(true);
               }
            break;
            
            /*default:
               return response()->json(false);
            break;*/
        }
    }
}
