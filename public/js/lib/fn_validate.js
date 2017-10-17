 $(function($) {
        $.validator.addMethod("chosenmultiple", function(val, element){
              var r = true;
              if (val==null || val=='' || val.length==0) {
                  $(element).siblings('.chosen-container').find(".chosen-choices").css('border-color','#a94442');
                  r = false
              }
               $("body").on("change",element, function(){
                  $(element).siblings('.chosen-container').find(".chosen-choices").css('border-color','');
                  $('label.error').remove();
              });
              return r;
        });

        $.validator.addMethod("editorsummer", function(val, element){
           var r = true;
            $(element).siblings('.note-editor').css('border-color','');
            if (val==null || val=='' || val.length==0) {
                $(element).siblings('.note-editor').css('border-color','#a94442');
                r = false
            }
            return r;
        });   
  });