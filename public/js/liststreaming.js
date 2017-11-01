(function($){
  "use strict";
  var aulaVirtual = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblstudents',
    table:'',
    user:'',
    course:'',
    cursos:[],
    //students:[],
    launch: function(){
      aulaVirtual.user=$('#title_aula').attr('data-user');
      this.listCourses();
      this.fnDatepicker();
      this.btnRegistry();
      this.listStudents();
      
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
    },btnRegistry:function(){
      $('#btnRegistry').click(function() {
          aulaVirtual.Modal('#modalRegistryEvents');
      });
    },Modal:function(modal){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() { 
              var select=$("#curso");  
              select.empty().append($("<option>",{value:''}).text('Seleccione'));
              $.each(aulaVirtual.cursos, function( i, val ) {
                  $.each(val, function( a, v ) {
                    select.append($('<option>',{'value':btoa(v.id)}).text(v.name));
                  });
              });
              $(".chosen-select").chosen();
              //$('.chosen-container input[type="text"]').attr("autocomplete",false);       
              select.trigger('chosen:updated');
              aulaVirtual.clearInputs();     
        })
    },clearInputs:function(){
        //limpiar campos
        $('.input-modal').each(function(){
            $(this).val('');
        });
    },fnDatepicker:function(){
        $('.datepicker').datepicker({
          format: 'dd/mm/yyyy',
          startDate: '-3d'
        }).on(
            'show', function() {      
            var zIndexModal = $('#modalRegistryEvents').css('z-index');
            var zIndexFecha = $('.datepicker').css('z-index');
            $('.datepicker').css('z-index',zIndexModal+1);
          });
    },listCourses:function(){
         var consult=aulaVirtual.consult('./listcourses',{'user':aulaVirtual.user},'GET');
          consult.done(function(d){ 
              aulaVirtual.cursos=d;
          });
    },listStudents:function(){
       $("body").on("change","#curso", function(){
          aulaVirtual.course=$(this).val();
          var consult=aulaVirtual.consult('../course/liststudents',{'course':aulaVirtual.course},'POST');
          consult.done(function(d){ 
              //aulaVirtual.cursos=d;
          });
       });
    }
  }
  $(document).ready(function(){
    aulaVirtual.launch();
  });
})(jQuery);
