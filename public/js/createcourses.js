(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    launch: function(){
      this.validateModal();
     this.fnChosen();
     this.fnSummernote();
     this.fnClockpicker();
     this.fnDatepicker();
     this.selectTeacher();
     this.saveCourse();
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
      select.append($('<option>',{'value':''}).text('Seleccione'));
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
        $("#form-create-course").validate();
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
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#temario" ).rules( "add", {
            required: true,
            messages: {
                required: "",
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
            var estatus_curso=$("input[name='estatus_curso']" ).val();
            var temario=$("#temario").val();
            var material=[];
            $('.material').each(function(){
                if($(this).is(':checked')){
                  material.push($(this).val());
                }
            })
            var create=courses.consult('./savecourse',
              {'name_course':name_course,'f_inicio':f_inicio,'f_fin':f_fin,
               'h_inicio':h_inicio,'h_fin':h_fin,'profesor':profesor,
               'estatus_curso':estatus_curso,'temario':temario,'material':material
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
            });
          }
      });
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
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
              if(typeof msj!==undefined || msj!='' || msj!=null ){
                $(".oper_mensaje").text(msj);
              }
              if(typeof title!==undefined || title!='' || title!=null ){
                $("#oper_titulo").text(title);
              }
              if(typeof error!==undefined || error!='' || error!=null ){
                $("#oper_error").text(error);
              }       
        })
    },
  }
  $(document).ready(function(){
    //courses.validateModal();
    courses.launch();
    //courses.saveCourse();
  });
})(jQuery);
