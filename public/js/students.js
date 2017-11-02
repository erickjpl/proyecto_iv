(function($){
  "use strict";
  var modStudent = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblstudents',
    table:'',
    data:[],
    user:[],
    launch: function(){
      this.dataTable(this.table_id);
      this.validateModal();
      this.activar();
      this.setEstatusUser();
      this.btnModifique();
      this.btnMod();
      this.btnDelete();
      this.DelUser();
      this.selectProfile();
      this.selectOcupations();
    },
    consult: function(url,params,type,async,btoa){
      if (url!=undefined && url.length>0 && typeof params === 'object') {
        var value="", data="", a=(async!=undefined||async==false?false:true), method=(type==undefined?this.typeAjax:type);
        for(var key in params){
          value = (this.btoaFieldsAjax?(btoa!=undefined||btoa==true?params[key]:btoa(params[key])):params[key]);
          data += (data.length<=0?key+'='+value:'&'+key+'='+value);
        }
        return $.ajax({
          type: method,
          url: url,
          data: data,
          dataType: 'json',
          async: a,
          headers: (method=="POST"?{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}:"")
        });
      }
    },dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
            "ajax": './studentslist',
            "language": {
              "lengthMenu": "Total de registros:_MENU_ ",
              "zeroRecords": "No se encontraron registros que mostrar",
              "info": "Total de p&aacute;ginas _PAGE_ de _PAGES_",
              "infoEmpty": "",
              "infoFiltered": "(filtered from _MAX_ total records)",
              "sSearch" :"Buscar: ",
              "sProcessing": "",
              "paginate": {
                  "previous": "Anterior",
                  "next": "Siguiente"
               }
             },
            "columns": [
                { "data": "name","sClass": 'text-center'  },
                { "data": "lastname","sClass": 'text-center'  },
                { "data": "email","sClass": 'text-center'  },
                { "data": "active","sClass": 'text-center'  },
                { "data": "active_select","sClass": 'text-center'  },               
                { "data": "actions","sClass": 'text-center' }
            ]
        },);
    },selectProfile:function(){
      var select=$("#perfil");
      select.append($('<option>',{'value':''}).text('Seleccione'));
      var select_profile=this.consult('./profileslist',[],'GET');
      select_profile.done(function(d){
          $.each( d, function( i, val ) {
              //if(val.active_select=='true'){
                select.append($('<option>',{'value':val.id}).text(val.name));
              //}
          });
      });
    },selectOcupations:function(){
      var select=$("#occupation");
      select.append($('<option>',{'value':''}).text('Seleccione'));
      var select_ocupations=this.consult('./ocupationslist',[],'GET');
      select_ocupations.done(function(d){
          $.each( d, function( i, val ) {
                select.append($('<option>',{'value':val.id}).text(val.name));
          });
      });
    },
    validateModal:function(){
        $("#form-create-user").validate();
        $( "#nombre" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#apellido" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#email" ).rules( "add", {
            required: true,
            email:true,
            messages: {
                required: "",
                email:"",
            }
        });
        $( "#perfil" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#occupation" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
          $( "#identification_document" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
    },activar:function(){
       $("body").on("change",".acc_profile", function(){
          modStudent.user.id=$(this).attr('data-user');
          modStudent.user.active=$(this).val();
          modStudent.Modal('#modal_estatus_usuario');
       });
    },Modal:function(modal){        
        $(modal).modal('show');
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
              if(typeof msj!==undefined || msj!='' || msj!=null ){
                $(".oper_mensaje").text(msj);
              }
              if(typeof title!==undefined || title!='' || title!=null ){
                $("#oper_titulo").text(title);
              }
              if(typeof error!==undefined || error!='' || error!=null ){
                $("#oper_error").text(error);
              }       
        })
    },setEstatusUser:function(){
      $("body").on("click","#setestatus_user", function(){
          var email_notif=$("#email_notif").val();
          var user=modStudent.user.id;
          var active=modStudent.user.active;
          var upd=modStudent.consult('./setuser',{'user':user,'active':active,'email_notif':email_notif},'POST');
          upd.done(function(d){ 
            var msj='Operacion realizada con éxito';
            var title='Mensaje!';
            var error='';
            if(d.oper==false){
               msj='Error comuniquese con el Administrador';
               title='Error!';
               error=(d.error!='')?d.error:'';
            }else{
              var id=modStudent.table;
              id.ajax.reload();
            }
            $('#modal_estatus_usuario').modal('hide');
            modStudent.Modaloper('#modal_operacion',msj,title,error);
         });
       });
    },ModalMod:function(modal,data_user){
        $(modal).modal({backdrop: 'static', keyboard: false});  
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
            
            $(".input-modal").each(function( index ) {
                $(this).val('').removeClass('error');
            });
            if(typeof data_user !== 'undefined' || data_user!=='' || data_user!==null ){
                $('#nombre').val(data_user.name);
                $('#apellido').val(data_user.lastname);
                $('#email').val(data_user.email);
                $('#email').val(data_user.email);
                $('#perfil').val(data_user.profile_id);
                $("#id-user-modal").val(btoa(data_user.id));
                $("#identification_document").val(data_user.identification_document);
                $("#occupation").val(data_user.occupation_id);
              }
        })
    },btnModifique:function(){
      $("body").on("click",".acc_mod", function(){
          $(".form-perfil").hide();
          modStudent.user.id=$(this).attr('data-user');
          modStudent.LoadModalUser(modStudent.user.id);
      });
    },LoadModalUser:function(id){
        var data=modStudent.consult('./getuser',{'id_user':id},'GET');
        data.done(function(d){
          var result=d[0];
          $( ".modalsave" ).each(function() {$(this).attr('id','moduser');});
          $("#registryLabel").text('Modificar Usuario');
          modStudent.ModalMod('#modalRegistry',result);
        });
    },btnMod:function(){
      $("body").on("click","#moduser", function(){
        if($("#form-create-user").valid()){
           var nombre = $("#nombre").val();
           var apellido = $("#apellido").val(); 
           var email = $("#email").val();
           var perfil = $("#perfil").val();
           var docid = $('#identification_document').val()
           var occupation = $("#occupation").val();
           var form={nombre:nombre,apellido:apellido,email:email,perfil:perfil,docid:docid,occupation:occupation,_method:'PUT'};
           var update=modStudent.consult('./moduser/'+modStudent.user.id,form,'POST');
           update.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=modStudent.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                modStudent.Modaloper('#modal_operacion',msj,title,error);
           });
        }
      });
    },btnDelete:function(){
      $("body").on("click",".acc_del", function(){
          modStudent.user.id=$(this).attr('data-user');
          modStudent.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
      });
    },DelUser:function(){
      $("body").on("click","#go_oper", function(){
         var delet=modStudent.consult('./deluser/'+modStudent.user.id,{_method:'DELETE'},'POST');
         delet.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=modStudent.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                modStudent.Modaloper('#modal_operacion',msj,title,error);
           });
      });     
    }
  }
  $(document).ready(function(){
    modStudent.launch();
  });
})(jQuery);
