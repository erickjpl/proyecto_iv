<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $file='emails';
    private $data_email=[];
    private $template='';
    private $remitente='carla.ramirez.rojas@gmail.com';
    private $name_rem='Carla Ramirez';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$template)
    {
        $this->data_email=$data;
        $this->template=$template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $data_user=$this->data_email;
        $file=$this->file.'.'.$this->template;
        switch ($this->template) {
            
        case 'msg_useractive':
            $txt_comple='';
            if($data_user["active"]=='true'){
                $txt_comple='Puede Autenticarse en la Plataforma. Bienvenido!'; 
            }
            $txt_active=($data_user["active"]=='true')?'Activa':'Inactivo';
            return $this->view($file)
                ->with(['name'=>$data_user["name"],
                        'active'=>$txt_active,
                        'complemento'=>$txt_comple
                ])->from($this->remitente,$this->name_rem)->subject('Notificación de Estatus de Cuenta');
        break;

        case 'msg_inscription':
            return $this->view($file)
                ->with(['start_date'=>$data_user["start_date"],
                        'end_date'=>$data_user["end_date"],
                        'name_course'=>$data_user["name_course"],
                        'name_user'=>$data_user["name_user"],
                ])->from($this->remitente,$this->name_rem)->subject('Inscripción del Curso '.$data_user["name_course"]);
        break;
        
        case 'msg_confinscription':
            return $this->view($file)
                ->with(['start_date'=>$data_user["start_date"],
                        'end_date'=>$data_user["end_date"],
                        'name_course'=>$data_user["name_course"],
                        'name_user'=>$data_user["name_user"],
                ])->from($this->remitente,$this->name_rem)->subject('Confirmación Inscripción del Curso '.$data_user["name_course"]);
        break;

        case 'msg_bajainscription':
            return $this->view($file)
                ->with(['name_course'=>$data_user["name_course"],
                        'name_user'=>$data_user["name_user"],
                ])->from($this->remitente,$this->name_rem)->subject('Inscripción del Curso '.$data_user["name_course"]);
        break;

        case 'msg_certificate':
            return $this->view($file)
                ->with(['name_course'=>$data_user["name_course"],
                        'name_user'=>$data_user["name_user"],
                ])->from($this->remitente,$this->name_rem)->subject('Certificado Aprobacioón del Curso '.$data_user["name_course"]);
        break;

        default:
            return false;
        break;
        }

    }
}
