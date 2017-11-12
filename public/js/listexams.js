(function($){
  "use strict";
  var listExams = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblexamsn',
    exam:'',
    estatus_exam:'',
    launch: function(){
      this.createView();
      this.dataTable(this.table_id);
      this.editExam();
      this.statusExam();
      this.accEstatus();
      this.deleteExam();
      this.confirDeleteExam();
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
    },createView:function(){
      $("#btnRegistry").on('click',function(){
          window.location.href = "./create";
      });
    },dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
            "ajax": './getexams',
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
                { "data": "name","sClass": 'namecoursetable'},
                { "data": "start_date","sClass": 'text-center'  },
                { "data": "end_date","sClass": 'text-center'  },
                { "data": "status","sClass": 'text-center'  },            
                { "data": "actions","sClass": 'text-center' }
            ]
        },);
    },editExam:function(){
      $("body").on("click",".acc_mod", function(){
          var course=$(this).attr('data-exam');
          window.location.href = "./"+course;

      });
    },statusExam:function(){
      $("body").on("click",".acc_status", function(){  
        listExams.exam=$(this).attr('data-exam'); 
        listExams.estatus_exam=$(this).attr('data-estatus');
        listExams.ModalEstatus('#modal_status_exam','Estatus Examen');
      });
    },ModalEstatus:function(modal,title){
        $(modal).modal({backdrop: 'static', keyboard: false}); 
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {             
            if(listExams.estatus_exam!='' || listExams.estatus_exam!=null ){
              $("#selectstatus_exam").val(listExams.estatus_exam);
            }
            $("#titulo_modal_exam").text(title);   
            $("#form_estatus_exam").validate({
              ignore: '*:not([name])', //Fixes your name issue*/
            });
            $( "#selectstatus_exam" ).rules( "add", {
                required: true,
                messages: {
                    required: ""
                }
            });        
        });
    },accEstatus:function(){
        $("body").on("click","#savestatus", function(){  
            var estatus=$("#selectstatus_exam").val();
            if($("#form_estatus_exam").valid()){
               var setestatus = listExams.consult("./updatestatus",{'est':estatus,'exam':listExams.exam},"POST",false);
               setestatus.done(function(d){
                  var msj='Operacion realizada con éxito';
                  var title='Mensaje!';
                  var error='';
                  if(d.oper==false){
                     msj='Error comuniquese con el Administrador';
                     title='Error!';
                     error=(d.error!='')?d.error:'';
                  }
                  listExams.Modaloper('#modal_operacion',msj,title,error);
                });
            }
        });
    },Modaloper:function(modal,msj,title,error){
        $('.modal').modal('hide');
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
        if(error==''){
          $(modal).on('hidden.bs.modal', function () {
              var id=listExams.table;
              id.ajax.reload();
                
          });
        }
    },deleteExam:function(){
        $("body").on("click",".acc_del", function(){ 
            listExams.exam=$(this).attr('data-exam'); 
            listExams.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
        }); 
    },confirDeleteExam:function(){
      $("body").on("click","#go_oper", function(){ 
          var delet=listExams.consult('./deletexam/'+listExams.exam,{_method:'DELETE'},'POST');
           delet.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=listExams.table;
                  id.ajax.reload();
                }
                listExams.Modaloper('#modal_operacion',msj,title,error);
           });
      });
      
    }
  }
  $(document).ready(function(){
    listExams.launch();
  });
})(jQuery);