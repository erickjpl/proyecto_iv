(function($){
  "use strict";
  var certif = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    data:[],
    course:'',
    launch: function(){
      certif.fnChosen();
      certif.listCourses();
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
    },fnChosen:function(){
      $(".chosen-select").chosen();
      $('.chosen-container input[type="text"]').attr("autocomplete",false);
    },listCourses:function(){
      var data=this.consult('certificates/liscourses',[],'GET');
      data.done(function(d){
        var select=$("#list_courses_eva");
        $.each( d, function( i, val ) {
            console.log(val);
            select.append($('<option>',{'value':val.id}).text(val.name));
        });
        select.trigger('chosen:updated');
      });
    }
  }
  $(document).ready(function(){
    certif.launch();
  });
})(jQuery);
