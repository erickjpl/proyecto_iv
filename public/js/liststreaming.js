(function($){
  "use strict";
  var aulaVirtual = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblstreamings',
    table:'',
    user:'',
    courseid:'',
    courses:[],
    students:[],
    streamingid:'',
    launch: function(){
      aulaVirtual.user=$('#title_aula').attr('data-user');
      this.dataTable(this.table_id);
      this.listCourses();
      this.fnDatepicker();
      this.btnRegistry();
      this.listStudents();
      this.saveUser();
      this.modEvent();
      this.updateEvent();
      this.btnDelete();
      this.DelEvent();
      this.finalizarEvento();
      this.btnFinalizarEvento();
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
        $(modal).modal({backdrop: 'static', keyboard: false});  
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() { 
              aulaVirtual.clearInputs();   
              aulaVirtual.validateModal();
              var select=$("#curso");  
              select.empty().append($("<option>",{value:''}).text('Seleccione'));
              $.each(aulaVirtual.courses, function( i, val ) {
                  $.each(val, function( a, v ) {
                    select.append($('<option>',{'value':btoa(v.id)}).text(v.name));
                  });
              });
              $(".chosen-select").chosen();
              $('#hora_inicio_streaming').clockpicker({align: 'left',placement:'top',donetext:"Seleccionar",twelvehour:true,default:'now'});
              //$('.chosen-container input[type="text"]').attr("autocomplete",false);       
              select.trigger('chosen:updated');
              
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
              aulaVirtual.courses=d;
          });
    },consultStudents(idcourse){
        var consult=aulaVirtual.consult('../course/liststudents',{'course':idcourse},'POST');
        consult.done(function(d){ 
            $(".registros").remove();
            $('#tb_users_reg').show();
            aulaVirtual.students=d;
            $.each(aulaVirtual.students, function( i, val ) {
                console.log(val);
                if(val.status=='true'){
                    $('#tb_users_reg').hide();
                    $('#tb_users').append($('<tr>',{class:'registros'}).append(
                      $('<td>').text(val.name+' '+val.lastname),
                      $('<td>').text(val.email)
                    ));
                }                
            });
        });
    },listStudents:function(){
       $("body").on("change","#curso", function(){
          $("#curso_chosen").find('.chosen-single').removeClass('chosen-error')
          aulaVirtual.courseid=$(this).val();
          aulaVirtual.consultStudents(aulaVirtual.courseid);
       });
    },saveUser:function(){
       $("body").on("click","#savestreaming", function(){
          if($("#form-create-streaming").valid()){
            var course=$("#curso").val();
            var descripcion=$("#descripcion").val();
            var url=$("#url").val();
            var fecha_inicio=$("#fecha_inicio_streaming").val();
            var h_inicio=$("#hora_inicio_streaming").val();
            var insert=aulaVirtual.consult('./eventsave',{'fecha_inicio':fecha_inicio,'course':course,'descripcion':descripcion,'url':url,'h_inicio':h_inicio},'POST');
            insert.done(function(d){ 
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=aulaVirtual.table;
                  id.ajax.reload();
                }
                aulaVirtual.Modaloper('#modal_operacion',msj,title,error);
            });
          }else{
            if($("#curso").val()==''){
               $("#curso_chosen").find('.chosen-single').addClass('chosen-error')
            }
          }
       });
    },validateModal:function(){
        $("#form-create-streaming").validate({
            validClass: "success",
        });
        $( "#curso" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#descripcion" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#url" ).rules( "add", {
            required: true,
            url: true,
            messages: {
                required: "",
                url:"",
            }
        });
        $( "#fecha_inicio_streaming" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#hora_inicio_streaming" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        
    },Modaloper:function(modal,msj,title,error){
        $('.modal').modal('hide');
        $(modal).modal('show');
        if(modal=='#modalRegistryEvents'){
          $(modal).modal({backdrop: 'static', keyboard: false});
          $('.modalstreaming').attr('id','savestreaming');  
        }
        $(modal).on('shown.bs.modal', function() {             
              aulaVirtual.clearInputs();
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
    },dataTable:function(idtable){
        this.table=$(idtable).DataTable( {
            "ajax": './liststreamings',
            "language": {
              "lengthMenu": "Total de registros:_MENU_ ",
              "zeroRecords": "No se encontraron registros que mostrar",
              "info": "Total de p&aacute;ginas _PAGE_ de _PAGES_",
              "infoEmpty": "",
              "infoFiltered": "(filtered from _MAX_ total records)",
              "sSearch" :"Buscar: ",
              "sProcessing": "",
              "paginate": {
                  "previous": "Anterior",
                  "next": "Siguiente"
               }
             },
            "columns": [
                { "data": "name","sClass": 'text-center'  },
                { "data": "description","sClass": 'text-center'  },
                { "data": "url","sClass": ''  },
                { "data": "start_date","sClass": 'text-center'  },           
                { "data": "actions","sClass": 'text-center' }
            ]
        },);
    },modEvent:function(){
         $("body").on("click",".acc_mod", function(){
            aulaVirtual.streamingid=$(this).attr('data-streaming');
            var consult=aulaVirtual.consult('./getstreaming',{'streaming':aulaVirtual.streamingid},'GET');
            consult.done(function(d){ 
              aulaVirtual.ModalMod('#modalRegistryEvents',d);
            });
         });
    },ModalMod:function(modal,data){
      $('.modal').modal('hide');
      $(modal).modal('show');
      $(modal).on('shown.bs.modal', function() { 
           $('.modalstreaming').attr('id','modtreaming');            
           var arrcourse=[];
           arrcourse.push(btoa(data[0].course_id)); 
           var select=$("#curso");  
            select.empty().append($("<option>",{value:''}).text('Seleccione'));
            $.each(aulaVirtual.courses, function( i, val ) {
                $.each(val, function( a, v ) {
                  select.append($('<option>',{'value':btoa(v.id)}).text(v.name));
                });
            });        
           $('#hora_inicio_streaming').clockpicker({align: 'left',placement:'top',donetext:"Seleccionar",twelvehour:true,default:'now'});
           select.chosen();
           select.val(arrcourse).trigger("liszt:updated");
           select.trigger('chosen:updated');
           $('#descripcion').val(data[0].description);
           $('#url').val(data[0].url);
           $('#fecha_inicio_streaming').val(data[0].start_date);
           $('#hora_inicio_streaming').val(data[0].h_inicio);
           aulaVirtual.consultStudents(btoa(data[0].course_id));
      })
    },updateEvent:function(){
        $("body").on("click","#modtreaming", function(){
          if($("#form-create-streaming").valid()){
            var course=$("#curso").val();
            var descripcion=$("#descripcion").val();
            var url=$("#url").val();
            var fecha_inicio=$("#fecha_inicio_streaming").val();
            var h_inicio=$("#hora_inicio_streaming").val();
            var update=aulaVirtual.consult('./eventmod/'+aulaVirtual.streamingid,{'_method':'PUT',
              'fecha_inicio':fecha_inicio,'h_inicio':h_inicio,'course':course,'descripcion':descripcion,'url':url},'POST');
            update.done(function(d){ 
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=aulaVirtual.table;
                  id.ajax.reload();
                }
                aulaVirtual.Modaloper('#modal_operacion',msj,title,error);
            });
          }else{
            if($("#curso").val()==''){
               $("#curso_chosen").find('.chosen-single').addClass('chosen-error')
            }
          }
        });
    },btnDelete:function(){
      $("body").on("click",".acc_del", function(){
          aulaVirtual.streamingid=$(this).attr('data-streaming');
          $(".aceptar_confirmarcion").attr('id','go_oper');
          aulaVirtual.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
      });
    },DelEvent:function(){
      $("body").on("click","#go_oper", function(){
         var delet=aulaVirtual.consult('./delevent/'+aulaVirtual.streamingid,{_method:'DELETE'},'POST');
         delet.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=aulaVirtual.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                aulaVirtual.Modaloper('#modal_operacion',msj,title,error);
           });
      });     
    },finalizarEvento:function(){
        $("body").on("click",".acc-inactivar", function(){
          aulaVirtual.streamingid=$(this).attr('data-streaming');
          $(".aceptar_confirmarcion").attr('id','go_finalizar');
          aulaVirtual.Modaloper('#modal_confirmacion','¿Desea realizar esta operación?','','');
      });
    },btnFinalizarEvento:function(){
      $("body").on("click","#go_finalizar", function(){
         console.log('hola');
         var updateevento=aulaVirtual.consult('./finevent/'+aulaVirtual.streamingid,{_method:'PUT'},'POST');
         updateevento.done(function(d){
                var msj='Operacion realizada con éxito';
                var title='Mensaje!';
                var error='';
                if(d.oper==false){
                   msj='Error comuniquese con el Administrador';
                   title='Error!';
                   error=(d.error!='')?d.error:'';
                }else{
                  var id=aulaVirtual.table;
                  id.ajax.reload();
                }
                $('#modalRegistry').modal('hide');
                aulaVirtual.Modaloper('#modal_operacion',msj,title,error);
           });
      });
      
    }
  }
  $(document).ready(function(){
    aulaVirtual.launch();
  });
})(jQuery);
