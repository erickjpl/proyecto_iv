(function($){
  "use strict";
  var listExams = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblexamsn',
    exam:'',
    launch: function(){
      this.createView();
      this.dataTable(this.table_id);
      this.editExam();
      this.statusExam();
      this.accEstatus();
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
        listExams.ModalEstatus('#modal_status_exam','Estatus Examen');
      });
    },ModalEstatus:function(modal,title){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {             
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
            if($("#form_estatus_exam").valid()){

            }
        });
    }
  }
  $(document).ready(function(){
    listExams.launch();
  });
})(jQuery);