(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    course_id:'',
    textQuestionSimple:"Pregunta Simple",
    textQuestionSelection:"Seleccion Multiple",
    row:"",
    contador:0,
    launch: function(){
      this.createForm();
      this.typeQuestion();
      this.addQuestion();
      this.fnChosen();
      this.getCourses();
      this.sendExam();
      this.exitExam();
      this.validForm();
    }
    ,exitExam:function(){
      $("#exit-exam").on('click',function(){
          window.location.href = "./list";
      });
    },
    getCourses:function(){
      var courses = this.consult("listcourses",{},"GET",false),data,r=$("#course");
      courses.done(function(d){
        for (var i in d) {
          r.append($("<option>",{"value":d[i].id}).text(d[i].name));
        }
      });
      return r;
    },
    fnDatepicker:function(){
        $('.datepicker').datepicker({
          format: 'dd/mm/yyyy',
          startDate: '-3d'
        });
    },
    fnClockpicker:function(){
      $('.clockpicker').clockpicker({donetext:"Seleccionar",twelvehour:true,default:'now'});
    },
    createForm:function(){
      var row="";
      this.row=$("<div>",{"class":"rowquestion form-group"}).append($("<label>",{"class":""}).append($("<div>",{"class":"question-number"})).text("Pregunta"),[$("<div>",{"class":"row"}).append(
          $("<div>",{"class":"col-xs-8"}).append(
              $("<input>",{"name":"question[]","type":"text","class":"description form-control required_exam","placeholder":"Question"}).val("")
          ),
          [
            $("<div>",{"class":"col-xs-2"}).append(
              $("<select>",{"name":"type[]","class":"select-question form-control"}).append(
                $("<option>",{'value':'off'}).text(this.textQuestionSimple),[
                  $("<option>",{'value':'on'}).text(this.textQuestionSelection)
                ]
              )
            ),
            $("<div>",{"class":"col-xs-2"}).append(
              $("<button>",{"class":"btn btn-success addQuestion col-xs-12"}).append($("<span>",{'class':'glyphicon glyphicon-plus-sign'})),[
                $("<button>",{"class":"btn btn-danger deleteQuestion col-xs-12"}).append($("<span>",{"class":"glyphicon glyphicon-remove-sign"}))
              ]
            )
          ])
      ])
      row=this.row.clone();
      row.find(".deleteQuestion").remove();
      $(".data-render").append(row);
      this.getCourses();
      this.fnClockpicker();
      this.fnDatepicker();
      $("#courses").trigger('chosen:updated');
    },
    typeQuestion:function(){
      $(".select-question").off("change");
      $(".select-question").on("change",function(e){
        courses.contador++;
        e.preventDefault(),e.stopPropagation();
        if($(this).val()==="on"){
          $(this).parents().eq(2).append(
            $("<div>",{"class":"row option-title"}).append($("<div>",{"class":"col-xs-12"}).append($("<label>",{"class":""}).text("Opciones"))),[
            $("<div>",{"class":"row option-row"}).append(
              $("<div>",{"class":"col-xs-10"}).append(
                  $("<input>",{"name":"option-"+courses.contador+"[]","class":"form-control required_exam","type":"text","placeholder":"Option"})
                ),
                $("<div>",{"class":"col-xs-2"}).append(
                  $("<button>",{"class":"btn btn-success addOption col-xs-6"}).append($("<span>",{'class':'glyphicon glyphicon-plus-sign'})),[
                    $("<button>",{"class":"btn btn-danger removeOption col-xs-6"}).append($("<span>",{"class":"glyphicon glyphicon-remove-sign"}))
                  ]
                )
            )]
          )         
          $(this).parent().siblings().children('.description').attr('name','question-'+courses.contador+'[]');//aqui
          courses.addOption();
          courses.deleteOption();
        }else{
          $(this).parent().eq(2).find(".option-row").remove();
        }
      });
    },
    addOption:function(){
      $(".addOption").off("click");
      $(".addOption").on("click",function(e){
        e.preventDefault(),e.stopPropagation();
        $(this).parents().eq(2).append(
          $("<div>",{"class":"row option-row"}).append(
          $("<div>",{"class":"col-xs-10"}).append(
            $("<input>",{"name":"option-"+courses.contador+"[]","class":"form-control required_exam","type":"text","placeholder":"Option"})
          ),[
            $("<div>",{"class":"col-xs-2"}).append(
              $("<button>",{"class":"btn btn-success addOption col-xs-6"}).append($("<span>",{'class':'glyphicon glyphicon-plus-sign'})),[
                $("<button>",{"class":"btn btn-danger removeOption col-xs-6"}).append($("<span>",{"class":"glyphicon glyphicon-remove-sign"}))
              ]
            )
          ]
          )
        )
        courses.addOption();
        courses.deleteOption();
      });
    },
    deleteOption:function(){
      $(".removeOption").off("click");
      $(".removeOption").on("click",function(e){
        e.preventDefault(),e.stopPropagation();
        if($(this).parents('.rowquestion').find('.option-row').length>1){
          $(this).parents().eq(1).remove();
        }
      });
    },
    addQuestion:function(){
      $(".addQuestion").off();
      $(".addQuestion").on("click",function(e){
        e.preventDefault(),e.stopPropagation();
        var x = courses.row.clone();
        x.find(".addQuestion").remove();
        $(this).parents().eq(3).append(x);
        courses.typeQuestion();
        courses.removeQuestion();
        courses.addOption();
      });
    },
    removeQuestion:function(){
      $(".deleteQuestion").off();
      $(".deleteQuestion").on("click",function(e){
        e.preventDefault(),e.stopPropagation();
        $(this).parents(".rowquestion").remove();
      });
    },
    fnChosen:function(){
      $(".chosen-select").chosen();
      $('.chosen-container input[type="text"]').attr("autocomplete",false);
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
         
        });
      }
    },sendExam:function(){
      $("#send-exam").on('click',function(){
        if($("#form-exam").valid()){
          var formData = new FormData($("#form-exam")[0]);
          $.ajax({
              type: "POST",
              url: './save',
              data: formData,
              cache: false,
              processData: false,
              contentType: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(value)
              {
                  var msj='Operacion realizada con éxito';
                  var title='Mensaje!';
                  var error='';
                  if(value.oper==false){
                     msj='Error comuniquese con el Administrador';
                     title='Error!';
                     error=(value.error!='')?value.error:'';
                  }
                  courses.Modaloper('#modal_operacion',msj,title,error);
              }
          });
          }
      }); 

    },validForm:function(){
        $("#form-exam").validate({
          ignore: '*:not([name])', //Fixes your name issue*/
        });
        $( "#hora_inicio" ).rules( "add", {
            required: true,
            messages: {
                required: ""
            }
        });
        $( "#hora_final" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#fecha_inicio" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( "#fecha_fin" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
        $( ".required_exam" ).rules( "add", {
            required: true,
            messages: {
                required: "",
            }
        });
    },Modaloper:function(modal,msj,title,error){
        $(modal).modal('show');
        $(modal).on('shown.bs.modal', function() {
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
        if(typeof error!==undefined || error!='' || error!=null ){
          $(modal).on('hidden.bs.modal', function () {
              window.history.back();
          });
        }
    },
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
