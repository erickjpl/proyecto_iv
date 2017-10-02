<?php

namespace App\Http\Controllers;

use App\Student;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use View;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class UsersController extends Controller
{
    
    use SendsPasswordResetEmails;

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
        $resp["data"]=array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $id=base64_encode($value->id);
                $true=($value->active==='true')?'selected':'';
                $false=($value->active==='false')?'selected':'';
                $select='<select name="active_profile" data-user="'.$id.'" class="acc_profile form-control">
                         <option '.$true.' value="true">Si</option>
                         <option '.$false.' value="false">No</option>
                         </select>';
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-user="'.$id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-user="'.$id.'">Eliminar</button>';
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
        $resp["data"]=array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $id=base64_encode($value->id);
                $true=($value->active==='true')?'selected':'';
                $false=($value->active==='false')?'selected':'';
                $select='<select name="active_profile" data-user="'.$id.'" class="acc_profile form-control">
                         <option '.$true.' value="true">Si</option>
                         <option '.$false.' value="false">No</option>
                         </select>';
                $actions='<button type="button" class="acc_mod btn btn-primary btn-xs" data-user="'.$id.'">Modificar</button>
                          <button type="button" class="acc_del btn btn-danger btn-xs" data-user="'.$id.'">Eliminar</button>';
                $value->active_select=$select;
                $value->active=($value->active=='true')?'Si':'No';
                $value->actions=$actions;
                $resp["data"][]=$value;
            }
        }
        return response()->json($resp);

    }

    /**
     * [store funcion que registra usuario]
     * @param  Request $request [campos del formulario]
     * @return [json]           [operacion]
     */
    public function store(Request $request)
    {   
        $objUser= new User();
        $save=$objUser->saveUser($request);
        $resp=response()->json($save);
        if($save["oper"]==true){
            $this->sendResetLinkEmail($request);
        }
        return $resp;
    }


    /**
     * [show guarda usuario]
     * @param  Request $request [object request]
     * @return [json]           []
     */
    public function show(Request $request)
    {
        $id_user=base64_decode($request->id_user);
        $objUser= new User();
        $data=$objUser->getUsers($id_user);
        return response()->json($data);
    }

    /**
     * [updateUser modifica usuario]
     * @param  Request $request [object request]
     * @param  [type]  $id      [id delusuario]
     * @return [json]           []
     */
    public function updateUser(Request $request, $id)
    {   
        $id=base64_decode($id);
        $objUser= new User();
        $data["name"]=$request->nombre;
        $data["lastname"]=$request->apellido;
        $data["email"]=$request->email;
        $data["updated_at"]=date("Y-m-d H:i:s");
        $obj = (object) $data;
        $data=$objUser->updateUser($obj,$id);
        return response()->json($data);

    }

    /**
     * [destroy elimina usuarios]
     * @param  [int] $id [id del usuario]
     * @return [json]     []
     */
    public function destroy($id)
    {
        $objUser= new User();
        $id=base64_decode($id);
        $data=$objUser->deleteUser($id);
        return response()->json($data);
    }

    /**
     * [setEstatusUser description]
     * @param Request $request [description]
     */
    public function setEstatusUser(Request $request){
       $objUser= new User();
       $id=base64_decode($request->user);
       $active=$request->active;
       $notif_mail=$request->email_notif;
       
       if($notif_mail==1){
        dd('aqio');
       }

       dd('alla');
       $data["active"]=$active;
       $obj = (object) $data;
       $update=$objUser->updateUser($obj,$id);
       return response()->json($data);
    }

}
