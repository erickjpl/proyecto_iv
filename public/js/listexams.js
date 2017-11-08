(function($){
  "use strict";
  var listExams = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    table_id:'#tblexamsn',
    launch: function(){
      this.createView();
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
    },createView:function(){
      $("#btnRegistry").on('click',function(){
          window.location.href = "./create";
      });
    }
  }
  $(document).ready(function(){
    listExams.launch();
  });
})(jQuery);
