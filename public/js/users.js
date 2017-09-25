(function($){
  "use strict";
  var modUsers = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table:'#tblusers',
    data:[],
    launch: function(){
      this.dataTable(this.table);
      this.selectProfile();
      this.validateModal();
      this.sendUser();
    },
    consult: function(url,params,type,async,btoa){
      if (url!=undefined && url.length>0 && typeof params === 'object') {
        var value="", data="", a=(async!=undefined||async==false?false:true), method=(type!=undefined?this.typeAjax:type);
        for(var key in params){
          value = (this.btoaFieldsAjax?(btoa!=undefined||btoa==true?params[key]:btoa(params[key])):params[key]);
          data += (data.length<=0?key+'='+value:'&'+key+'='+value);
        }
        return $.ajax({
          type: method,
          url: url,
          data: data,
          dataType: 'json',
          async: a
        });
      }
    },
    dataTable:function(idtable){
        $(idtable).DataTable( {
            "ajax": './userslist',
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
                { "data": "lastname","sClass": 'text-center'  },
                { "data": "email","sClass": 'text-center'  },
                { "data": "active","sClass": 'text-center'  },
                { "data": "active_select","sClass": 'text-center'  },               
                { "data": "actions","sClass": 'text-center' }
            ]
        },);
    },selectProfile:function(){
      var select=$("#perfil");
      select.append($('<option>',{'value':''}).text('Seleccione'));
      var select_profile=this.consult('./profileslist',[],'GET');
      select_profile.done(function(d){
          $.each( d, function( i, val ) {
              select.append($('<option>',{'value':val.id}).text(val.name));
          });
      });
    },validateModal:function(){
        $("#registro-usuario").validate({
            validClass: "success"
        });
        $( "#nombre" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#apellido" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#email" ).rules( "add", {
            required: true,
            email:true,
            messages: {
                required: "",
                email:"",
            }
        });
        $( "#perfil" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
    },sendUser:function(){
      $("#saveuser").click(function() {
        if($("#registro-usuario").valid()){
           
        }
      });
    }
  }
  $(document).ready(function(){
    modUsers.launch();

  });
})(jQuery);
