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

        $.validator.addMethod("validMail", function(val, element){
              var test=false;
              $.ajax({
                type: "GET",
                url: 'validajax/validmail/'+val,
                data: {},
                async:false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(value)
                {
                    var val=$.parseJSON(value);
                    if(val===true){
                      test=true;
                    }
                }
            });
            return test;
        });
  });