(function($){
  "use strict";
  var myClassRoom = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    data:[],
    course_id:'',
    launch: function(){
      this.course_id=$('#panel-clasroom').attr('data-course')
      this.listStreaming();
      this.refresh();
      this.getFiles();
      this.listExams();
      this.refreshExam();
      this.viewComent();
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
    getLocalDate: function(){
      return moment().format("YYYY/MM/DD HH:MM:ss");
    },refresh:function(){
      $("#refresh").on("click",function(){
        myClassRoom.listStreaming();  
      });
    },listStreaming:function(){
        var data=this.consult('./listStreaming/'+myClassRoom.course_id,[],'GET');
          data.done(function(d){
              if(d.length!=0){
                $("#title_course").text(d[0].name.toUpperCase());
                $('#tbstreamings').empty();
                $.each(d, function( k, v ) {
                    var start_date=moment(v.start_date_val).format("YYYY/MM/DD HH:MM:ss");
                    var est='<span class="label label-success label-status">Por Iniciar</span>';
                    var url='<a target="blank" class="btn btn-danger" href="'+v.url+'"><i class="glyphicon glyphicon-facetime-video"></i>&nbsp;Youtube</a>';
                    if(new Date(v.start_date_val).getTime() <= new Date(myClassRoom.getLocalDate()).getTime() && v.status=='true'){
                      est='<span class="label label-warning label-status">En Curso</span>';
                    }else if(v.status=='false'){
                      est='<span class="label label-danger label-status">Finalizado</span>';
                      url='<a target="blank" disabled class="btn btn-danger" ><i class="glyphicon glyphicon-facetime-video"></i>&nbsp;Youtube</a>';
                    }                 

                    $('#tbstreamings').append(
                      $('<tr>').append(
                          $('<td>').text(v.start_date),
                          $('<td>').html(est),
                          $('<td>').html(url)
                      ));
                });
              }else{
                $('#tbstreamings').append(
                    $('<tr>').append('<td>',{colspan:3}).text('No se encontraron Registros'));
              }
          });
    },getFiles:function(){
      var data=this.consult('./getFiles/'+myClassRoom.course_id,[],'GET');
      data.done(function(d){
          if(d.files.length>0){
             $.each(d.files, function( k, v ) {
                var url='<a target="blank" href="'+v.route+'">'+v.name+'</a>';
                $('#tbfiles').append(
                  $('<tr>').append(
                      $('<td>').html(url)));
            });
             $.each(d.files_manager, function( k, v ) {
                $("#descripcionfile").append($('<div>',{class:'alert alert-warning'}).text(v.description));
            });
          }else{
            $('#descripcionfile').text('No se han cargado material para este curso');
            $('#tbfiles').append(
                $('<tr>').append('<td>',{colspan:1}).text('No se encontraron Registros'));
          }
      });
    },listExams:function(){
      var data=this.consult('./getExams/'+myClassRoom.course_id,[],'GET');
      data.done(function(d){
            $('#tbexams').empty();
            if(d.length>0){
              $.each(d, function( k, v ) {
                console.log(v);
                var exam=btoa(v.id)
                var course=btoa(v.name);
                var cali='<span class="bold">Sin Evaluar</span>';
                var coment='<button  disabled class="btn btn-warning acc_coment" title="Sin Comentarios"><i class="glyphicon glyphicon-comment"></i></button>';
                var url='<a target="blank" class="btn btn-success" title="Presentar Examen" href="./exam/'+exam+'/'+course+'"><i class="glyphicon glyphicon-list-alt"></a>';
                if(new Date(v.end_date_val).getTime() <= new Date(myClassRoom.getLocalDate()).getTime() || v.status=='F' || v.examen_finalizado==false){
                  url='<a class="btn btn-danger" title="Examen Finalizado" disabled href=""><i class="glyphicon glyphicon-list-alt"></a>';
                }
                if(typeof v.calificacion.qualification != "undefined"){         
                  if(v.calificacion.qualification=='A'){
                    cali='<i title="Aprobado" class="glyphicon glyphicon-ok aprobexam"></i>';
                  }else if(v.calificacion.qualification=='R'){  
                    cali='<i title="Reprobado" class="glyphicon glyphicon-remove rechazexam"></i>';
                  }
                }
                if(typeof v.calificacion.coment != "undefined"){ 
                  coment='<button class="btn btn-warning acc_coment" title="Comentarios" data-coment="'+v.calificacion.coment+'"><i class="glyphicon glyphicon-comment"></i></button>';
                }
                $('#tbexams').append(
                  $('<tr>').append(
                      $('<td>',{class:'bold'}).text(v.start_date),
                      $('<td>',{class:'bold'}).text(v.end_date),
                      $('<td>').html(url),
                      $('<td>',{class:''}).html(coment),
                      $('<td>',{class:''}).html(cali)));
                });
            }else{
                $('#tbexams').append(
                $('<tr>').append('<td>',{colspan:1}).text('No se encontraron Registros'));
            }
            
      });
    },refreshExam:function(){
      $("#refresh_exam").on("click",function(){
        myClassRoom.listExams();  
      });
    },viewComent:function(){
      $("body").on("click",".acc_coment", function(){
          console.log('aqui');
          var text=$(this).attr('data-coment');
          $("#modal_informacion").modal('show');
          $("#modal_informacion").on('shown.bs.modal', function() { 
              $("#titulo_informacion").text('Información del Evaluador').css('font-weight','bold'); 
              $("#mensaje_informacion").html(text).css('font-weight','bold').css('text-aling','justify');                 
          })
      });
      
    }
  }
  $(document).ready(function(){
    myClassRoom.launch();
  });
})(jQuery);
