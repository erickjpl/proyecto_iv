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
    },activar:function(){
       $("body").on("change",".acc_profile", function(){
          modStudent.user.id=$(this).attr('data-user');
          modStudent.user.active=$(this).val();
          modStudent.Modal('#modal_estatus_usuario','','','','');
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
               error=(result.error!='')?result.error:'';
            }else{
              var id=modStudent.table;
              id.ajax.reload();
            }
            $('#modal_estatus_usuario').modal('hide');
            modStudent.Modaloper('#modal_operacion',msj,title,error);
         });
       });
    }
  }
  $(document).ready(function(){
    modStudent.launch();
  });
})(jQuery);
