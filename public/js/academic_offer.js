(function($){
  "use strict";
  var ofertaCursos = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    data:[],
    course:{},
    launch: function(){
      this.optionsViews();
      this.inscripcion();
      this.confirmacionInscripcion();
      this.modalMasinfo();
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
       var data=this.consult('./listacademic',[],'GET');
       data.done(function(d){
         this.data=d.courses;
          $.each(this.data, function( i, val ) {
             $.each(val, function( k, v ) {
              $("#body_courses").append(
                  $('<div>',{class:'bs-calltoaction bs-calltoaction-info'}).append(
                    $('<div>',{class:'row'}).append(
                        $('<div>',{class:'col-md-9 cta-contents'}).append($('<h1>',{class:'cta-title'}).html(v.name),
                          [$('<div>',{class:'cta-desc'}).append(
                              $('<p>').text('Fecha de Inicio: '+v.start_date),
                              $('<p>').text('Fecha Final: '+v.end_date),
                              $('<p>').text('Profesores: '+v.teacher).addClass('bold')
                          )]),
                            $('<div>',{class:'col-md-3 cta-button'}).append(
                                $('<a>',{class:'btn btn-lg btn-block btn-info add-course','data-course':btoa(v.id),'data-course-name':v.name}).text('Inscribirse'),
                                $('<a>',{class:'btn btn-lg btn-block btn-info add-masinfo','data-course':btoa(v.id)}).text('Mas Información'))
                        )));
              });
          });
      });
    },inscripcion:function(){
      $("body").on("click",".add-course", function(){
          ofertaCursos.course.id=$(this).attr('data-course');
          ofertaCursos.course.name=$(this).attr('data-course-name');
          ofertaCursos.Modaloper('#modal_confirmacion','¿Desea inscribirse en el curso <b>'+ofertaCursos.course.name.toUpperCase()+'</b>?','','');
      });
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {             
              if(typeof msj!==undefined || msj!='' || msj!=null ){
                $(".oper_mensaje").html(msj).addClass('bold');
              }
              if(typeof title!==undefined || title!='' || title!=null ){
                $("#oper_titulo").text(title);
              }
              if(typeof error!==undefined || error!='' || error!=null ){
                $("#oper_error").text(error);
              }       
        })
    },confirmacionInscripcion:function(){
      $("body").on("click","#go_oper", function(){
           var data=ofertaCursos.consult('./saveinscription',{'course':ofertaCursos.course.id},'POST');
            data.done(function(d){
                var msj='Inscripción realizada con éxito<br>Recibira un Email de confirmación';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   if(d.error='COD: 400'){
                    msj='Ya se encuentra inscrito en el curso <b>'+ofertaCursos.course.name.toUpperCase()+'</b>';
                   }
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }
                ofertaCursos.Modaloper('#modal_operacion',msj,title,error);
           });
      });
    },modalMasinfo:function(){
      $("body").on("click",".add-masinfo", function(){
          ofertaCursos.course.id=$(this).attr('data-course');
          $("#modal_informacion").modal('show');
          $("#modal_informacion").on('shown.bs.modal', function() { 
           var data_course=ofertaCursos.consult('course/datacourse',{'id':ofertaCursos.course.id},'POST');         
            data_course.done(function(d){ 
              var data_course=d.course[0];
              $("#titulo_informacion").text('Descripción del Temario').css('font-weight','bold'); 
              $("#mensaje_informacion").html(data_course.temary);  
            });            
               
          })

      });
    }
  }
  $(document).ready(function(){
    ofertaCursos.launch();
  });
})(jQuery);
