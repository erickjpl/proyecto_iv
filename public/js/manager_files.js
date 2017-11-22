(function($){
  "use strict";
  var mangFiles = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    user:'',
    course_id:'',
    descrip_course:'',
    files:[],
    input:"",
    launch: function(){
      mangFiles.user=$('#body-files').attr('data-user');
      this.input=$("#input-fa").clone();
      this.fnChosen();
      this.listCourses();
      this.fnInputFile();
      this.descriptionText();
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
    },listCourses:function(){
         var consult=mangFiles.consult('./listcourses',{'user':mangFiles.user},'GET',false);
          consult.done(function(d){ 
            var select=$("#courses_files");  
            select.empty().append($("<option>",{value:''}).text('Seleccione'));
             $.each(d[""], function( a, v ) {
                select.append($('<option>',{'value':btoa(v.id)}).text(v.name));
            });
            select.trigger('chosen:updated');
          });
    },fnInputFile:function(){          
      $.getScript( "../js/lib/fileinput/fileinput.js", function() {
        $.getScript( "../js/lib/fileinput/es.js", function() {
             mangFiles.selectCourse();
        });
      });
    },objetFileInput:function(course,descrip,listfiles){
      var ipreview=[],ipreviewconfig=[],data="";
      for(var x in listfiles){
        data = listfiles[x];
        ipreview.push("../materialCursos/curso_"+atob(this.course_id)+"_"+atob(this.user)+"/"+data.name);
        ipreviewconfig.push({caption: data.name, url: "./deletefile",key:data.id, extra: {'_token':$('#csrf_token').val(),'curso':this.course_id}});
      }
      $('#descripcion').val(descrip);
      $(".removeInput").empty().append(this.input);
      $("#input-fa").fileinput({
          theme: "fa",
          language: "es",
           uploadUrl: "./savefiles",
           uploadAsync: false,
           minFileCount: 1,
           maxFileCount: 5,
           overwriteInitial: true,
           initialPreviewAsData: true,
           allowedFileExtensions: ['jpg', 'png', 'docx','pptx','pdf','txt','xls','xlsx'],
           initialPreview: ipreview,
           maxFileSize:'5120KB',
           initialPreviewConfig: ipreviewconfig,
          uploadExtraData:{'_token':$('#csrf_token').val(),'curso':course,'descripcion':descrip}
       });
    },selectCourse:function(){
      $("body").on("change","#courses_files", function(){
          if($(this).val()!=''){
            mangFiles.course_id=$(this).val();
            mangFiles.consultFilesCourse(mangFiles.course_id);
            if($('#descripcion').val()!=''){
              mangFiles.descrip_course=$('#descripcion').val();
              $('#file-manager').removeClass('hide');
              //mangFiles.objetFileInput(mangFiles.course_id,mangFiles.descrip_course,mangFiles.files);
             }
          }else{
            $('#file-manager').addClass('hide');
          }         
      });
    },descriptionText:function(){
      $("body").on("change","#descripcion", function(){
          var value = $(this).val();
          if(value!=''){
            mangFiles.descrip_course=$('#descripcion').val();
            if(mangFiles.course_id!=''){
              $('#file-manager').removeClass('hide');
              mangFiles.objetFileInput(mangFiles.course_id,mangFiles.descrip_course,mangFiles.files);  
            }else{
              $('#file-manager').addClass('hide');
            }
          }else{
            $('#file-manager').addClass('hide');
          }     
      });
    },consultFilesCourse:function(){
      var x = $.ajax({
          type: "GET",
          url: './listfiles/'+mangFiles.course_id,
          data: "",
          dataType: 'json',
          async: true,
      });
      x.done(function(d){
        if(d[0]){
          $('#file-manager').removeClass('hide');
          mangFiles.descrip_course=d[0].description;
          mangFiles.files=d;
          mangFiles.objetFileInput(mangFiles.course_id,mangFiles.descrip_course,mangFiles.files);
        }else{
          mangFiles.objetFileInput(mangFiles.course_id,'',[]);
        }
      }); 
    }
  }
  $(document).ready(function(){
    mangFiles.launch();
    if(mangFiles.course_id!==''){
      mangFiles.fnInputFile(mangFiles.course_id);
    }
  });
})(jQuery);
