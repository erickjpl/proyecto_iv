(function($){
  "use strict";
  var modStudent = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table:'#tblstudents',
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
            "ajax": './studentslist',
            "columns": [
                { "data": "name" },
                { "data": "lastname" },
                { "data": "email" },
                { "data": "active" },
                { "data": "actions" }
            ]
        },);
    }
  }
  $(document).ready(function(){
    modStudent.launch();
  });
})(jQuery);
