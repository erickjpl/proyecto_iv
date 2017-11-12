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
      var ret="",from="",objdate="",month="", date="", objhour="", hour="",seg="",hourNumber="", meridiem="",min="";
      objdate = new Date();
      month = (objdate.getMonth() < 10? "0"+objdate.getMonth() : objdate.getMonth()+1);
      ret = objdate.getDate()+"/"+month+"/"+objdate.getFullYear();
      min = (objdate.getMinutes() < 10? "0"+objdate.getMinutes() : objdate.getMinutes());
      seg = (objdate.getSeconds() < 10? "0"+objdate.getSeconds() : objdate.getSeconds());
      hourNumber = (objdate.getHours() <= 12? objdate.getHours() : objdate.getHours()-12);
      meridiem = (objdate.getHours() <= 12? "AM" : "PM");
      ret +=" "+hourNumber+":"+min+":"+seg+" "+meridiem;
      return ret;
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
                    var est='';
                    if(v.status=='true'){
                      if(new Date(v.start_date) <= new Date(myClassRoom.getLocalDate())){
                        est='<span class="label label-warning label-status">En Curso</span>';
                      }else{
                        est='<span class="label label-success label-status">Por Iniciar</span>';
                      }                   
                    var url='<a target="blank" class="btn btn-danger" href="'+v.url+'"><i class="glyphicon glyphicon-facetime-video"></i>&nbsp;Youtube</a>';
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
                var url='<a target="blank" class="btn btn-success" title="Presentar Examen" href="./exam/'+exam+'/'+course+'"><i class="glyphicon glyphicon-list-alt"></a>';
                if(new Date(v.end_date) <= new Date(myClassRoom.getLocalDate()) || v.status=='F' || v.examen_finalizado==false){
                  url='<a class="btn btn-danger" title="Examen Finalizado" disabled href=""><i class="glyphicon glyphicon-list-alt"></a>';
                }
                $('#tbexams').append(
                  $('<tr>').append(
                      $('<td>',{class:'bold'}).text(v.start_date),
                      $('<td>',{class:'bold'}).text(v.end_date),
                      $('<td>').html(url)));
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
    }
  }
  $(document).ready(function(){
    myClassRoom.launch();
  });
})(jQuery);
