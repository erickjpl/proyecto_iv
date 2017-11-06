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
    },listStreaming:function(){
        var data=this.consult('./listStreaming/'+myClassRoom.course_id,[],'GET');
          data.done(function(d){
              if(d.length!=0){
                $("#title_course").text(d[0].name.toUpperCase());
                $.each(d, function( k, v ) {
                    var est='';
                    var url=v.url;
                    if(v.status=='true'){
                     est='<span class="label label-success label-status">Por Iniciar</span>';
                     url='<a target="blank" href="'+url+'">'+url+'</a>';
                    }else if(v.status=='false'){
                     est='<span class="label label-danger label-status">Finalizado</span>'; 
                    }
                    $('#tbstreamings').append(
                      $('<tr>').append(
                          $('<td>').text(v.start_date),
                          $('<td>').html(url),
                          $('<td>').html(est)
                      ));
                });
              }else{
                $('#tbstreamings').append(
                    $('<tr>').append('<td>',{colspan:3}).text('No se encontraron Registros'));
              }
          });
    }
  }
  $(document).ready(function(){
    myClassRoom.launch();
  });
})(jQuery);
