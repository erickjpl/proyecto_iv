(function($){
  "use strict";
  var courses = {
    btoaFieldsAjax:false,
    typeAjax:"GET",
    course_id:'',
    textQuestionSimple:"Pregunta Simple",
    textQuestionSelection:"Seleccion Multiple",
    row:"",
    launch: function(){
      this.createForm();
      this.typeQuestion();
      this.addQuestion();
      this.fnChosen();
    },
    createForm:function(){
      var select = $("<select>",{"id":"courses","class":"form-control chosen-select"}),
      row=$("<div>",{"class":"rowquestion"}).append($("<div>",{"class":"row"}).append(
          $("<div>",{"class":"col-xs-8"}).append(
            $("<input>",{"type":"text","class":"description form-control","placeholder":"Question"}).val("")
          ),
          [
            $("<div>",{"class":"col-xs-2"}).append(
              $("<select>",{"class":"select-question form-control"}).append(
                $("<option>",{'value':'off'}).text(this.textQuestionSimple),[
                  $("<option>",{'value':'on'}).text(this.textQuestionSelection)
                ]
              )
            ),
            $("<div>",{"class":"col-xs-2"}).append(
              $("<button>",{"class":"btn btn-success addQuestion"}).text("+"),[
                $("<button>",{"class":"btn btn-danger deleteQuestion"}).text("-")
              ]
            )
          ])
      )
      this.row=row.clone();
      $(".panel-body").append(select.add(this.row));
    },
    typeQuestion:function(){
      $(".select-question").off("change");
      $(".select-question").on("change",function(e){
        e.preventDefault(),e.stopPropagation();
        if($(this).val()==="on"){
          $(this).parents().eq(2).append(
            $("<div>",{"class":"row option-row"}).append(
              $("<div>",{"class":"col-xs-8"}).append(
                $("<input>",{"class":"form-control","type":"text","placeholder":"Option"})
              ),[
                $("<div>",{"class":"col-xs-4"}).append(
                  $("<button>",{"class":"btn btn-success addOption"}).text("+"),[
                    $("<button>",{"class":"btn btn-danger removeOption"}).text("-")
                  ]
                )
              ]
            )
          )
          courses.addOption();
          courses.deleteOption();
        }else{
          $(this).parents().eq(2).find(".option-row").remove();
        }
      });
    },
    addOption:function(){
      $(".addOption").off("click");
      $(".addOption").on("click",function(e){
        e.preventDefault(),e.stopPropagation();
        $(this).parents().eq(2).append(
          $("<div>",{"class":"row option-row"}).append(
          $("<div>",{"class":"col-xs-8"}).append(
            $("<input>",{"class":"form-control","type":"text","placeholder":"Option"})
          ),[
            $("<div>",{"class":"col-xs-4"}).append(
              $("<button>",{"class":"btn btn-success addOption"}).text("+"),[
                $("<button>",{"class":"btn btn-danger removeOption"}).text("-")
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
        console.log(courses.row.get(0));
        $(this).parents().eq(3).append(courses.row.clone());
        courses.typeQuestion();
        courses.addOption();
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
          headers: (method=="POST"?{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}:"")
        });
      }
    }
  }
  $(document).ready(function(){
    courses.launch();
  });
})(jQuery);
