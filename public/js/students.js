(function($){
  "use strict";
  var modStudent = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table:'#tblstudents',
    data:[],
    user:'',
    launch: function(){
      this.dataTable(this.table);
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
        $(idtable).DataTable( {
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
          modStudent.user=$(this).attr('data-user');
          modStudent.Modal('#modal_estatus_usuario','','','','');
       });
    },Modal:function(modal){
        
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
    
        })
        $(modal).on("hidden.bs.modal", function () {
            $(".modal-backdrop").each(function(){
              $(this).remove();
            });
        });
    },setEstatusUser:function(){
       $("#setestatus_user").click(function() {
          var email_notif=$("#email_notif").val();
          var data=modUsers.consult('./setuser',{'id_user':modStudent.user,'email_notif':email_notif},'POST');
       });
    }
  }
  $(document).ready(function(){
    modStudent.launch();
  });
})(jQuery);
