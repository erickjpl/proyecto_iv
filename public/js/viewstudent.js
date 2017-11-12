(function($){
  "use strict";
  var viewExam = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    exam_id:'',
    course_id:'',
    launch: function(){
      $("#f_enddate").text($("#body-exam").attr('data-fecha'));
      this.exam_id=$("#body-exam").attr('data-exam');
      this.course_id=$("#body-exam").attr('data-course');
      this.listQuestions();
      this.sendExam();
      this.confirmExam();
      this.salirExam();
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
    },listQuestions:function(){
      console.log();
      var questions=this.consult('../../../getQuestions/'+viewExam.exam_id,[],'GET',false);
      questions.done(function(d){
          $.each(d, function( k, v ) {
              console.log(v);
              $('#body_questions').append($('<div>',{class:'form-group'}).append(
                $('<label>').text(v.description.toUpperCase()),
                $('<div>',{'data-question':v.id}).append('')));

               if(v.type=='c'){
                  $('#body_questions').find('[data-question='+v.id+']').append(
                    $('<textarea>',{class:'form-control',name:'answer_'+v.id})
                  ); 
               }else if(v.type=='o'){
                  var opciones=v.options.split(',');
                  $.each(opciones, function( a, val ) {
                      $('#body_questions').find('[data-question='+v.id+']').addClass('checkbox').append(
                        $('<label>').append(
                          $('<input>',{type:'checkbox',name:'answer_'+v.id+'[]','value':val}),
                          $('<b>').append(val)
                        ).add('<br>'));
                  });
               }
               
          });
      });
    },sendExam:function(){
      $("body").on("click","#saveexam", function(){
        viewExam.Modaloper('#modal_confirmacion','¿Esta seguro de finalizar el examen?','','',false);
      }); 
    },Modaloper:function(modal,msj,title,error,cierre){
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
        if(error=='' && cierre==true){
          $(modal).on('hidden.bs.modal', function () {
              window.close();
          });
        }
    },confirmExam:function(){
        $("body").on("click","#go_oper", function(){
            var formData = new FormData($("#form_questions")[0]);
            formData.append('exam_id',viewExam.exam_id);
            formData.append('course_id',viewExam.course_id);
            $.ajax({
                type: "POST",
                url: '../../saveQuestions',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(value)
                {
                    var msj='El examen fue enviado con exito. La Calificación la podra visualizar en su Aula Virtual';
                    var title='Mensaje!';
                    var error='';
                    if(value.oper==false){
                       msj='Error comuniquese con el Administrador';
                       title='Error!';
                       error=(value.error!='')?value.error:'';
                    }
                    viewExam.Modaloper('#modal_operacion',msj,title,error,true);
                }
            });
        });
    },salirExam:function(){
      $("body").on("click","#salirexam", function(){
        window.close();
      });
    }
  }
  $(document).ready(function(){
    viewExam.launch();
  });
})(jQuery);
