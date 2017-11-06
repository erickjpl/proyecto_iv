(function($){
  "use strict";
  var ofertaCursos = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    data:[],
    launch: function(){
      this.optionsViews();
      this.redirectCourse();
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
    },optionsViews:function(){
       var data=this.consult('./listmycourses',[],'GET');
       data.done(function(d){
         this.data=d;
          $.each(this.data, function( i, v ) {
              $("#body_courses_student").append(
                  $('<div>',{class:'bs-calltoaction bs-calltoaction-info'}).append(
                    $('<div>',{class:'row'}).append(
                        $('<div>',{class:'col-md-9 cta-contents'}).append($('<h1>',{class:'cta-title'}).html(v.name),
                          [$('<div>',{class:'cta-desc'}).append(
                              $('<p>').text('Fecha de Inicio: '+v.start_date),
                              $('<p>').text('Fecha Final: '+v.end_date),
                              $('<p>').text('Profesores: '+v.teacher)
                          )]),
                            $('<div>',{class:'col-md-3 cta-button'}).append(
                              $('<a>',{class:'btn btn-lg btn-block btn-info view-course','data-course':btoa(v.id)}).html('<i class="glyphicon glyphicon-dashboard"></i>&nbsp;Ver Aula'))
                        )));
          });
      });
    },redirectCourse:function(){
      $("body").on("click",".view-course", function(){
          var course=$(this).attr('data-course');
          window.location="coursestudent/"+course;
      });
    }
  }
  $(document).ready(function(){
    ofertaCursos.launch();
  });
})(jQuery);
