(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblcourses',
    table:'',
    list_students:[],
    course:'',
    user:{},
    //user:[],
    launch: function(){
      this.addCourse();
      this.dataTable(this.table_id);
      this.modCourse();
      this.activeStudents();
      this.checkInactive();
      this.activarUser();
      this.delCourse();
      this.confirmDelCourse();
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
    },addCourse:function(){
      $("#btnaddCourse").click(function() {
        window.location.href = "./createcourse";
      });
    },dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
            "ajax": './getcourses',
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
                { "data": "name","sClass": 'namecoursetable'  },
                { "data": "start_date","sClass": 'text-center'  },
                { "data": "end_date","sClass": 'text-center'  },
                { "data": "status","sClass": 'text-center'  },            
                { "data": "actions","sClass": 'text-center' }
            ]
        },);
    },modCourse:function(){
       $("body").on("click",".acc_mod", function(){
          $(".form-perfil").hide();
          var course=$(this).attr('data-course');
          window.location.href = "./"+course;
      });
    },activeStudents:function(){
        $("body").on("click",".acc_act", function(){  
          courses.course=$(this).attr('data-course'); 
          var liststudents=courses.consult('liststudents',{'course':courses.course},'POST');
            liststudents.done(function(d){   
                courses.modalStudents('#modal_list_students',d);
            });
        });
    },modalStudents:function(modal,data){
       $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {             
              $("#list_students").empty();
              $.each(data, function( k, v ) {
                var status=v.status;
                var active=(status=='true')?true:false;
                var inactive=(status=='false')?true:false;
                $('#list_students').append($('<table>',{class:'table'}).append(
                    $('<tr>').append(
                    $('<td>').text(v.name+' '+v.lastname).css('font-weight','bold'),
                    $('<td>').html($('<input>',{'data-user':btoa(v.user_id),'data-user':btoa(v.user_id),'data-status':status,type:'radio',value:'true',checked:active,class:'check_val'}).add($('<label>').text('Activo'))),
                    $('<td>').html($('<input>',{'data-user':btoa(v.user_id),'data-status':status,type:'radio',value:'false',checked:inactive,class:'check_val'}).add($('<label>').text('Inactivo'))),
                )));
              }); 
        });
    },checkInactive:function(){
         $("body").on("change",".check_val", function(){  
           var status_act=$(this).attr('data-status');
           courses.user.id=$(this).attr('data-user');
           if($(this).val()!=status_act){
              courses.user.status=$(this).val();
              $("#email_notif").val(0);
              courses.Modal('#modal_estatus_usuario');
           }
        });
    },Modal:function(modal){
        $('.modal').modal('hide');
        $(modal).modal('show');
    },activarUser:function(){
      $('#setestatus_user').click(function() {
          var val_mail=($("#email_notif").val()==0)?false:true;
          var update=courses.consult('setstudent',{'course_id':courses.course,'user_id':courses.user.id,'user_status':courses.user.status,'notif':val_mail},'POST');
          update.done(function(d){   
            var msj='Operacion realizada con éxito';
            var title='Mensaje!';
            var error='';
            if(d.oper==false){
               msj='Error comuniquese con el Administrador';
               title='Error!';
               error=(d.error!='')?d.error:'';
            }
            courses.Modaloper('#modal_operacion',msj,title,error);
          });
      });
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
              if(typeof msj!==undefined || msj!='' || msj!=null ){
                $(".oper_mensaje").text(msj).addClass('bold');
              }
              if(typeof title!==undefined || title!='' || title!=null ){
                $("#oper_titulo").text(title);
              }
              if(typeof error!==undefined || error!='' || error!=null ){
                $("#oper_error").text(error);
              }       
        })
    },delCourse:function(){
      $("body").on("click",".acc_del", function(){  
        courses.course=$(this).attr('data-course'); 
        courses.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
      });
    },confirmDelCourse:function(){
      $("body").on("click","#go_oper", function(){
         var delet=courses.consult('./delcourse/'+courses.course,{_method:'DELETE'},'POST');
         delet.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=courses.table;
                  id.ajax.reload();
                }
                courses.Modaloper('#modal_operacion',msj,title,error);
           });
      });     
      
    }
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
