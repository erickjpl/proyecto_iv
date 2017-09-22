<?php

namespace App\Http\Controllers;

use App\Student;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use View;

class UsersController extends Controller
{
    

    public function indexStudents()
    {
        return View::make('users.students')->withName('steve');
    }

    public function indexUsers()
    {
        return View::make('users.users')->withName('steve');
    }  

    /**
     * [listStudents retorna lista de estudiantes
     * renderiza Datatable]
     * @return [json] 
     */
    public function listStudents(){
        $objStudent= new Student();
        $data=$objStudent->getStudents();
        if(!empty($data)){
            $resp=array();
            foreach ($data as $key => $value) {
                $true=($value->active==='true')?'selected':'';
                $false=($value->active==='false')?'selected':'';
                $select='<select name="active_profile" data-user="'.$value->id.'" class="acc_profile form-control">
                         <option '.$true.' value="true">Si</option>
                         <option '.$false.' value="false">No</option>
                         </select>';
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-user="'.$value->id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-user="'.$value->id.'">Eliminar</button>';
                $value->active_select=$select;
                $value->active=($value->active=='true')?'Si':'No';
                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);
    }

    /**
     * [listUsers retorna lista de usuarios
     * renderiza Datatable]
     * @return [json] 
     */
    public function listUsers(){
        $objUser= new User();
        $objProfile= new Profile();
        $profiles[]=$objProfile->pro_admin;
        $profiles[]=$objProfile->pro_teacher;
        $data=$objUser->getUsers('',$profiles);
        if(!empty($data)){
            $resp=array();
            foreach ($data as $key => $value) {
                $true=($value->active==='true')?'selected':'';
                $false=($value->active==='false')?'selected':'';
                $select='<select name="active_profile" data-user="'.$value->id.'" class="acc_profile form-control">
                         <option '.$true.' value="true">Si</option>
                         <option '.$false.' value="false">No</option>
                         </select>';
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-user="'.$value->id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-user="'.$value->id.'">Eliminar</button>';
                $value->active_select=$select;
                $value->active=($value->active=='true')?'Si':'No';
                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        //
    }*/
}
