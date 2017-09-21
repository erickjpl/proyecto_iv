<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('users.students')->withName('steve');
    }  

    /**
     * [listStudents retorna lista de estudiantes]
     * @return [json] 
     */
    public function listStudents(){
        $objStudent= new Student();
        $data=$objStudent->getStudents();
        if(!empty($data)){
            $resp=array();
            foreach ($data as $key => $value) {
                $true=($value->active=='true')?'checked':'';
                $false=($value->active=='false')?'checked':'';
                $select='<select name="active_profile" data-user="'.$value->id.'" class="acc_profile form-control">
                         <option '.$true.' value="true">Si</option>
                         <option '.$false.' value="false">No</option>
                         </select>';
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-user="'.$value->id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-user="'.$value->id.'">Eliminar</button>';
                $value->active=$select;
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
