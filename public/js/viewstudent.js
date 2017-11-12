(function($){
  "use strict";
  var viewExam = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    exam_id:'',
    launch: function(){
      $("#f_enddate").text($("#body-exam").attr('data-fecha'));
      this.exam_id=$("#body-exam").attr('data-exam');
      this.listQuestions();
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
    },listQuestions:function(){
      console.log();
      var questions=this.consult('../../../getQuestions/'+viewExam.exam_id,[],'GET',false);
      questions.done(function(d){
          $.each(d, function( k, v ) {
              console.log(v);
              $('#body_questions').append($('<div>',{class:'form-group'}).append(
                $('<label>').text(v.description.toUpperCase()),
                $('<div>',{'data-question':v.id}).append('')));

               if(v.type=='c'){
                  $('#body_questions').find('[data-question='+v.id+']').append(
                    $('<textarea>',{class:'form-control',name:'answer_'+v.id})
                  ); 
               }else if(v.type=='o'){
                  var opciones=v.options.split(',');
                  $.each(opciones, function( a, val ) {
                      $('#body_questions').find('[data-question='+v.id+']').addClass('checkbox').append(
                        $('<label>').append(
                          $('<input>',{type:'checkbox',name:'answer_'+v.id+'[]','value':val}),
                          $('<b>').append(val)
                        ).add('<br>'));
                  });
               }
               
          });
      });
    }
  }
  $(document).ready(function(){
    viewExam.launch();
  });
})(jQuery);
