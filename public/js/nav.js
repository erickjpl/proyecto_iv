(function($){
  "use strict";
  var profileNav = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    profile_id:'',
    launch: function(){
      profileNav.profile_id=$('#app-navbar-collapse').attr('data-profile');
      profileNav.profile_id=parseInt(atob(profileNav.profile_id));
      profileNav.listNav();
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
    },listNav:function(){
      $('.nav-app').addClass('hide');
      switch (profileNav.profile_id) {
        case 1: //administrador         
          $('.nav-stu').removeClass('hide');
        break;
        case 2: //administrador       
          $('.nav-adm').removeClass('hide');
        break;
        case 3: //profesores       
          $('.nav-tea').removeClass('hide');
        break;
      }
    }
  }
  $(document).ready(function(){
    profileNav.launch();
  });
})(jQuery);
