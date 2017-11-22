(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    course_id:'',
    launch: function(){
     this.fnChosen();
     this.fnSummernote();
     this.fnClockpicker();
     this.fnDatepicker(); 
     this.selectTeacher();
     this.getCourse();
     this.saveCourse();
     this.exitCourse();
     this.validateModal();
     this.modCourse();
    },fnChosen:function(){
      $(".chosen-select").chosen();
      $('.chosen-container input[type="text"]').attr("autocomplete",false);
    },fnSummernote:function(){
      $('.summernote').summernote({
          height: 150,
          lang: 'es-ES',
          tabsize: 2,
          minHeight: null,          
          maxHeight: null,
          placeholder: 'Escriba el temario del Curso',          
          focus: true 
        });
    },fnDatepicker:function(){
        $('.datepicker').datepicker({
          format: 'dd/mm/yyyy',
          startDate: '-3d'
        });
    },fnClockpicker:function(){
      $('.clockpicker').clockpicker({donetext:"Seleccionar",twelvehour:true,default:'now'});
    },consult: function(url,params,type,async,btoa){
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
    },selectTeacher:function(){
      var select=$("#profesor");
      var select_profile=this.consult('./teacherslist',[],'GET');
      select_profile.done(function(d){
          $.each( d, function( i, val ) {
              if(val.active=='true'){
                select.append($('<option>',{'value':val.id}).text(val.name+' '+val.lastname));
              }
               select.trigger('chosen:updated');
          });

      });
      
    },validateModal:function(){

        $("#form-create-course").validate({
          ignore: '*:not([name])', //Fixes your name issue*/
        });
        $( "#nombre_curso" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#hora_inicio" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#hora_final" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#fecha_inicio" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#fecha_fin" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
         $( "#profesor" ).rules( "add", {
            chosenmultiple: true,
            messages: {
                chosenmultiple: ""
            }
        });
         $( "#temario" ).rules( "add", {
            editorsummer: true,
            messages: {
                editorsummer: ""
            }
        });

     },saveCourse:function(){
       $("body").on("click","#savecourse", function(){
        if($("#form-create-course").valid()){
            var name_course=$("#nombre_curso").val();
            var f_inicio=$("#fecha_inicio").val();
            var h_inicio=$("#hora_inicio").val();
            var f_fin=$("#fecha_fin").val();
            var h_fin=$("#hora_final").val();
            var profesor=$("#profesor").val();
            var estatus_curso=$("input[name='estatus_curso']:checked" ).val();
            var tem=$("#temario").val();
            var material=[];
            $('.material').each(function(){
                if($(this).is(':checked')){
                  material.push($(this).val());
                }
            })
            $.ajax({
              type: "POST",
              url: './savecourse',
              data: {'name_course':name_course,'f_inicio':f_inicio,'f_fin':f_fin,
               'h_inicio':h_inicio,'h_fin':h_fin,'profesor':profesor,
               'estatus_curso':estatus_curso,'temario':tem,'material':material
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(d)
              {
                 var msj='Operacion realizada con éxito';
                  var title='Mensaje!';
                  var error='';
                  if(d.oper==false){
                     msj='Error comuniquese con el Administrador';
                     title='Error!';
                     error=(d.error!='')?d.error:'';
                  }
                  courses.Modaloper('#modal_operacion',msj,title,error);
              }
            });

            /*var create=courses.consult('./savecourse',
              {'name_course':name_course,'f_inicio':f_inicio,'f_fin':f_fin,
               'h_inicio':h_inicio,'h_fin':h_fin,'profesor':profesor,
               'estatus_curso':estatus_curso,'temario':tem,'material':material
              },'POST');
            create.done(function(d){ 
              var msj='Operacion realizada con éxito';
              var title='Mensaje!';
              var error='';
              if(d.oper==false){
                 msj='Error comuniquese con el Administrador';
                 title='Error!';
                 error=(d.error!='')?d.error:'';
              }
              courses.Modaloper('#modal_operacion',msj,title,error);
            });*/
          }
      });
    },Modaloper:function(modal,msj,title,error){
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
        if(typeof error!==undefined || error!='' || error!=null ){
          $(modal).on('hidden.bs.modal', function () {
              window.history.back();
          });
        }
    },exitCourse:function(){
      $("#salircourse").click(function() {
        window.history.back();
      });
    },getCourse:function(){
      var course=$("#id_course").val();
      if(course!=''){
          courses.course_id=course;
          var data_course=courses.consult('datacourse',
              {'id':course},'POST');         
          data_course.done(function(d){ 
            var data_course=d.course[0];
            var data_teacher=[];
              $.each(d.teachers, function( i, val ) {
                data_teacher.push(val);
              });
              $("#nombre_curso").val(data_course.name);
              $("#fecha_inicio").val(data_course.f_inicio);
              $("#fecha_fin").val(data_course.f_fin);
              $("#hora_inicio").val(data_course.h_fin);
              $("#hora_final").val(data_course.h_fin);
              $("#temario").val(data_course.temary);
              $("#temario").val(data_course.temary);
              $(".note-editable").html(data_course.temary);                 
              $("#profesor").val(data_teacher).trigger("liszt:updated");
              $("#profesor").trigger('chosen:updated');

              if(data_course.status=='true'){
                 $("#curso_true").attr('checked',true);
              }else if(data_course.status=='false'){
                $("#curso_true").attr('checked',false);
              }
              $(".material").each(function(){
                  $(this).attr('checked',false);
              });
              if(data_course.streaming=='true'){
                  $("#tipo_mat1").attr('checked',true);
              }
              if(data_course.exams=='true'){
                  $("#tipo_mat2").attr('checked',true);
              }
              $(".btn-createcourse").attr("id",'modcourse');
          });        
      }
    },modCourse:function(){
        $("body").on("click","#modcourse", function(){
          if($("#form-create-course").valid()){
              var name_course=$("#nombre_curso").val();
              var f_inicio=$("#fecha_inicio").val();
              var h_inicio=$("#hora_inicio").val();
              var f_fin=$("#fecha_fin").val();
              var h_fin=$("#hora_final").val();
              var profesor=$("#profesor").val();
              var estatus_curso=$("input[name='estatus_curso']:checked" ).val();
              var temario=$("#temario").val();
              var material=[];
              $('.material').each(function(){
                  if($(this).is(':checked')){
                    material.push($(this).val());
                  }
              })
              $.ajax({
                  type: "POST",
                  url: './updatecourse/'+courses.course_id,
                  data: {'name_course':name_course,'f_inicio':f_inicio,'f_fin':f_fin,
                   'h_inicio':h_inicio,'h_fin':h_fin,'profesor':profesor,
                   'estatus_curso':estatus_curso,'temario':temario,'material':material,_method:'PUT'
                  },
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(d)
                  {
                     var msj='Operacion realizada con éxito';
                      var title='Mensaje!';
                      var error='';
                      if(d.oper==false){
                         msj='Error comuniquese con el Administrador';
                         title='Error!';
                         error=(d.error!='')?d.error:'';
                      }
                      courses.Modaloper('#modal_operacion',msj,title,error);
                  }
                });

              /*var update=courses.consult('./updatecourse/'+courses.course_id,
                {'name_course':name_course,'f_inicio':f_inicio,'f_fin':f_fin,
                 'h_inicio':h_inicio,'h_fin':h_fin,'profesor':profesor,
                 'estatus_curso':estatus_curso,'temario':temario,'material':material,_method:'PUT'
                },'POST');
              update.done(function(d){ 
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }
                courses.Modaloper('#modal_operacion',msj,title,error);
              });*/
          }
        });
      }
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
