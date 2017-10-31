(function($){
  "use strict";
  var modUsers = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblusers',
    table:'',
    data:[],
    user:[],
    launch: function(){
      this.dataTable(this.table_id);
      this.selectProfile();
      this.selectOcupations();
      this.validateModal();
      this.saveUser();
      this.btnRegistry();
      this.btnModifique();
      this.modifUser();
      this.btnDelete();
      this.DelUser();
      this.activar();
      this.setEstatusUser();
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
    },
    dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
            "ajax": './userslist',
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
              if(val.active_select=='true'){
                select.append($('<option>',{'value':val.id}).text(val.name));
              }
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
    },validateModal:function(){
        $("#form-create-user").validate({
            validClass: "success"
        });
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
            integer:true,
            messages: {
                required: "",
                integer: "",
            }
        });
    },saveUser:function(){
       $("body").on("click","#saveuser", function(){
        if($("#form-create-user").valid()){
           var nombre = $("#nombre").val();
           var apellido = $("#apellido").val(); 
           var email = $("#email").val();
           var perfil = $("#perfil").val();
           var docid = $("#identification_document").val();
           var occupation = $("#occupation").val();
           var form={nombre:nombre,apellido:apellido,email:email,perfil:perfil,active:'true',occupation:
            occupation,docid:docid};
           var save=modUsers.consult('./saveuser',form,'POST');
           save.done(function(d){
              var msj='Operacion realizada con éxito';
              var title='Mensaje!';
              var error='';
              if(d.oper==false){
                 msj='Error comuniquese con el Administrador';
                 title='Error!';
                 error=(d.error!='')?d.error:'';
              }else{
                var id=modUsers.table;
                id.ajax.reload();
              }
              console.log('aqui');
              $('#modalRegistry').modal('hide');
              modUsers.Modaloper('#modal_operacion',msj,title,error);
           });
        }
      });
    },modifUser:function(){
      $("body").on("click","#moduser", function(){
        if($("#form-create-user").valid()){
           var nombre = $("#nombre").val();
           var apellido = $("#apellido").val(); 
           var email = $("#email").val();
           var perfil = $("#perfil").val();
           var docid = $('#identification_document').val()
           var occupation = $("#occupation").val();
           var form={nombre:nombre,apellido:apellido,email:email,perfil:perfil,docid:docid,occupation:occupation,_method:'PUT'};
           var update=modUsers.consult('./moduser/'+modUsers.user.id,form,'POST');
           update.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=modUsers.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                modUsers.Modaloper('#modal_operacion',msj,title,error);
           });
        }
      });
    },DelUser:function(){
      $("body").on("click","#go_oper", function(){
         var delet=modUsers.consult('./deluser/'+modUsers.user.id,{_method:'DELETE'},'POST');
         delet.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=modUsers.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                modUsers.Modaloper('#modal_operacion',msj,title,error);
           });
      });     
    },clearInputs:function(){
        //limpiar campos
        $('.input-modal').each(function(){
            $(this).val('');
        });
    },Modal:function(modal){
        $(modal).modal('show');
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {             
              modUsers.clearInputs();
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
    },ModalMod:function(modal,data_user){

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
                $("#identification_document").val(data_user.identification_document);
                $("#occupation").val(data_user.occupation_id);

              }
        })

    },btnRegistry:function(){
      $('#btnRegistry').click(function() {
          $(".form-perfil").show();
          $( ".modalsave" ).each(function( index ) {$(this).attr('id','saveuser');});
          $("#registryLabel").text('Registrar Usuario');
          modUsers.Modaloper('#modalRegistry','','','');
      });
    },btnModifique:function(){
      $("body").on("click",".acc_mod", function(){
          $(".form-perfil").hide();
          modUsers.user.id=$(this).attr('data-user');
          modUsers.LoadModalUser(modUsers.user.id);
      });
    },btnDelete:function(){
      $("body").on("click",".acc_del", function(){
          modUsers.user.id=$(this).attr('data-user');
          modUsers.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
      });
    },LoadModalUser:function(id){
        var data=modUsers.consult('./getuser',{'id_user':id},'GET');
        data.done(function(d){
          var result=d[0];
          $( ".modalsave" ).each(function() {$(this).attr('id','moduser');});
          $("#registryLabel").text('Modificar Usuario');
          modUsers.ModalMod('#modalRegistry',result);
        });
    },activar:function(){
       $("body").on("change",".acc_profile", function(){
          modUsers.user.id=$(this).attr('data-user');
          modUsers.user.active=$(this).val();
          modUsers.Modal('#modal_estatus_usuario');
       });
    },setEstatusUser:function(){
      $("body").on("click","#setestatus_user", function(){
          var email_notif=$("#email_notif").val();
          var user=modUsers.user.id;
          var active=modUsers.user.active;
          var upd=modUsers.consult('./setuser',{'user':user,'active':active,'email_notif':email_notif},'POST');
          upd.done(function(d){ 
            var msj='Operacion realizada con éxito';
            var title='Mensaje!';
            var error='';
            if(d.oper==false){
               msj='Error comuniquese con el Administrador';
               title='Error!';
               error=(d.error!='')?d.error:'';
            }else{
              var id=modUsers.table;
              id.ajax.reload();
            }
            $('#modal_estatus_usuario').modal('hide');
            modUsers.Modaloper('#modal_operacion',msj,title,error);
         });
       });
    },
  }
  $(document).ready(function(){
    modUsers.launch();
  });
})(jQuery);
