(function($){
  "use strict";
  var evaluations = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblevaluations',
    table:'',
    course:'a',
    exam:'b',
    user_eva:'',
    answer_eva:'',
    launch: function(){
      this.fnChosen();
      this.listCourses();
      this.listExams();
      this.dataTable(this.table_id);
      this.selectExam();
      this.accEvaluar();
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
    },fnChosen:function(){
      $(".chosen-select").chosen();
      $('.chosen-container input[type="text"]').attr("autocomplete",false);
    },listCourses:function(){
      var select=$("#list_courses_eva");
      var select_courses=this.consult('evaluations/listcourses',[],'GET');
      select_courses.done(function(d){
          $.each( d, function( i, val ) {
              select.append($('<option>',{'value':val.id}).text(val.name));
          });
          select.trigger('chosen:updated');
      });
    },listExams:function(){
      $("body").on("change","#list_courses_eva", function(){
          if($(this).val()!=''){
              var course=$("#list_courses_eva option:selected").val();;
              evaluations.course=course;
              var select_exam=$("#list_exams_eva");
              var data_exam=evaluations.consult('evaluations/listexams/'+btoa(course),[],'GET');
              data_exam.done(function(d){
                   if(d.length>0){
                    var a=1;
                     $.each( d, function( i, val ) {
                          if(val.type=='F'){
                            var name='Final';
                          }else{
                            var name='Parcial '+a;
                            a++;
                          }
                          select_exam.append($('<option>',{'value':val.id}).text(name));
                      });
                      select_exam.trigger('chosen:updated');
                   }
              });
          }else{
             $("#list_exams_eva").empty();
             $("#list_exams_eva").append($('<option>',{value:''}).text('Seleccione'));
             $("#list_exams_eva").trigger('chosen:updated');
          }
      });
    },dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
          "ajax": {
                'type': 'GET',
                'url': 'evaluations/listevaluations/'+btoa(evaluations.course)+'/'+btoa(evaluations.exam),
                'data': {},
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
            },
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
                { "data": "email","sClass": 'text-center'  },
                { "data": "qualification","sClass": 'namecoursetable'  }
            ]
        },);
    },selectExam:function(){
        $("body").on("change","#list_exams_eva", function(){
          if($(this).val()!=''){
              evaluations.exam=$("#list_exams_eva option:selected").val();
              evaluations.table.ajax.url( "evaluations/listevaluations/"+btoa(evaluations.course)+"/"+btoa(evaluations.exam) ).load();
          }
        });
    },accEvaluar:function(){
       $("body").on("click",".acc-eva", function(){
            evaluations.answer_eva=$(this).attr('data-answer'); 
            evaluations.user_eva=$(this).attr('data-user'); 
            evaluations.modalEva();
       });
    },modalEva:function(){
        $("#modal_eva_exam").modal({backdrop: 'static', keyboard: false})  
        $("#modal_eva_exam").modal('show');
        $("#modal_eva_exam").on('shown.bs.modal', function() {
              var answers=evaluations.consult('evaluations/getAnswer/'+btoa(evaluations.answer_eva),[],'GET');
              answers.done(function(d){

              });
        });
    }
  }
  $(document).ready(function(){
    evaluations.launch();
  });
})(jQuery);
