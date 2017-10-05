(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    launch: function(){
     
     this.fnSummernote();
     this.fnClockpicker();
     this.fnDatepicker();
     this.selectTeacher();
     this.validateModal();
     this.saveCourse();


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
          });
      });
    },validateModal:function(){
        $("#form-create-course").validate({
            validClass: "success"
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
          
        }
      });
    }
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
