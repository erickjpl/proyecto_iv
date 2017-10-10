(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblcourses',
    table:'',
    //data:[],
    //user:[],
    launch: function(){
      this.addCourse();
      this.dataTable(this.table_id);
      this.modCourse();
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
                { "data": "name","sClass": 'text-center'  },
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
          window.location.href = "./course/"+course;
      });
    }
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
