(function($){
  "use strict";
  var modUsers = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table:'#tblusers',
    data:[],
    launch: function(){
      this.dataTable(this.table);
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
    }
  }
  $(document).ready(function(){
    modUsers.launch();
  });
})(jQuery);
