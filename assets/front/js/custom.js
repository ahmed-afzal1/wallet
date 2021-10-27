$(function ($) {
    "use strict";


    $(document).ready(function () {

    //**************************** CUSTOM JS SECTION ****************************************

    // LOADER
      if(loader == 1)
      {
        $(window).on("load", function (e) {
          setTimeout(function(){
              $('#preloader').fadeOut(1000);
            },1000)
        });    
      }

    // LOADER ENDS

      //  Alert Close
      $("button.alert-close").on('click',function(){
        $(this).parent().hide();
      });


    //More Categories
    $('.rx-parent').on('click', function() {
            $('.rx-child').toggle();
            $(this).toggleClass('rx-change');
        });

    

    //  FORM SUBMIT SECTION

    $(document).on('submit','#contactform',function(e){
      e.preventDefault();
      $('.gocover').show();
      $('button.submit-btn').prop('disabled',true);
          $.ajax({
           method:"POST",
           url:$(this).prop('action'),
           data:new FormData(this),
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
           {
              if ((data.errors)) {
              $('.alert-success').hide();
              $('.alert-danger').show();
              $('.alert-danger ul').html('');
                for(var error in data.errors)
                {
                  $('.alert-danger ul').append('<li>'+ data.errors[error] +'</li>')
                }
                $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').eq(0).focus();
                $('.refresh_code').trigger('click');

              }
              else
              {
                $('.alert-danger').hide();
                $('.alert-success').show();
                $('.alert-success p').html(data);
                $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').eq(0).focus();
                $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').val('');
                $('.refresh_code').trigger('click');

              }
              $('.gocover').hide();
              $('button.submit-btn').prop('disabled',false);
           }

          });

    });  

    //  FORM SUBMIT SECTION ENDS

    //  QUOTE SUBMIT SECTION

    $(document).on('submit','#quoteform',function(e){
      e.preventDefault();
      $('.gocover').show();
      $('button.submit-btn').prop('disabled',true);
      $('#sub-btn').prop('disabled',true);
          $.ajax({
           method:"POST",
           url:$(this).prop('action'),
           data:new FormData(this),
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
           {
              if ((data.errors)) {

                for(var error in data.errors) {
                  $.notify(data.errors[error],"error");
                }
              }
              else {
                 $('#subemail').val('');
                 $.notify(data,"success");
              }

              $('.quote-submit-btn').prop('disabled',false);
              $('.quote-input').val('');
              $('.quote-input-text').html('');
              $('.quote-input-check').prop('checked',false);
              $('#user-login').modal('hide');
              $('#sub-btn').prop('disabled',false);
              $('.gocover').hide();
           }

          });

    });  

    //  QUOTE SUBMIT SECTION ENDS


    //  SUBSCRIBE FORM SUBMIT SECTION

    $(document).on('submit','#subscribeform',function(e){
      e.preventDefault();
      $('#sub-btn1').prop('disabled',true);
          $.ajax({
           method:"POST",
           url:$(this).prop('action'),
           data:new FormData(this),
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
           {
              if ((data.errors)) {

                for(var error in data.errors) {
                  $.notify(data.errors[error],"error");
                }
              }
              else {
                 $('#subemail').val('');
                 $.notify(data,"success");
              }

              $('#sub-btn1').prop('disabled',false);

              $('#subemail').val('');
           }

          });

    });  



    //  SUBSCRIBE FORM SUBMIT SECTION ENDS

        // REGISTER FORM
        $("#registerform").on('submit', function (e) {
          var $this = $(this).parent();
          e.preventDefault();
          $this.find('button.submit-btn').prop('disabled', true);
          $this.find('.alert-info').show();
          $this.find('.alert-info p').html($('.processdata').val());
          $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
    
              if (data == 1) {
                window.location = mainurl + '/user/dashboard';
              } else {
    
                if ((data.errors)) {
                  $this.find('.alert-success').hide();
                  $this.find('.alert-info').hide();
                  $this.find('.alert-danger').show();
                  $this.find('.alert-danger ul').html('');
                  for (var error in data.errors) {
                    $this.find('.alert-danger p').html(data.errors[error]);
                  }
                  $this.find('button.submit-btn').prop('disabled', false);
                } else {
                  $this.find('.alert-info').hide();
                  $this.find('.alert-danger').hide();
                  $this.find('.alert-success').show();
                  $this.find('.alert-success p').html(data);
                  $this.find('button.submit-btn').prop('disabled', false);
                }
    
              }
              $('.refresh_code').click();
    
            }
    
          });
    
        });
        // REGISTER FORM ENDS

        // LOGIN FORM
        $("#loginform").on('submit', function (e) {
          var $this = $(this).parent();
          e.preventDefault();
          $this.find('button.submit-btn').prop('disabled', true);
          $this.find('.alert-info').show();
          $this.find('.alert-info p').html($('#authdata').val());
          $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
              console.log(data.errors)
              if ((data.errors)) {
                $this.find('.alert-success').hide();
                $this.find('.alert-info').hide();
                $this.find('.alert-danger').show();
                $this.find('.alert-danger ul').html('');
                for (var error in data.errors) {
                  $this.find('.alert-danger p').html(data.errors[error]);
                }
              } else {
                $this.find('.alert-info').hide();
                $this.find('.alert-danger').hide();
                $this.find('.alert-success').show();
                $this.find('.alert-success p').html('Success !');
                if (data == 1) {
                  location.reload();
                } else {
                  window.location = data;
                }
    
              }
              $this.find('button.submit-btn').prop('disabled', false);
            }
    
          });
    
        });
        // LOGIN FORM ENDS


// Currency and Language Section

        $(".selectors").on('change',function () {
          var url = $(this).val();
          window.location = url;
        });

// Currency and Language Section Ends



//==============CONTACT FORM ===========================

$(document).on('submit','#contactform',function(e){
  e.preventDefault();
  $('.gocover').show();
  $('button.submit-btn').prop('disabled',true);
      $.ajax({
       method:"POST",
       url:$(this).prop('action'),
       data:new FormData(this),
       contentType: false,
       cache: false,
       processData: false,
       success:function(data)
       {
          if ((data.errors)) {
          $('.alert-success').hide();
          $('.alert-danger').show();
          $('.alert-danger ul').html('');
            for(var error in data.errors)
            {
              $('.alert-danger ul').append('<li>'+ data.errors[error] +'</li>')
            }
            $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').eq(0).focus();
            $('.refresh_code').trigger('click');

          }
          else
          {
            $('.alert-danger').hide();
            $('.alert-success').show();
            $('.alert-success p').html(data);
            $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').eq(0).focus();
            $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').val('');
            $('.refresh_code').trigger('click');

          }
          $('.gocover').hide();
          $('button.submit-btn').prop('disabled',false);
       }

      });

});

$(document).on('submit','#simpleform',function(e){
  e.preventDefault();
  
      $.ajax({
       method:"POST",
       url:$(this).prop('action'),
       data:new FormData(this),
       dataType:'JSON',
       contentType: false,
       cache: false,
       processData: false,
       success:function(data)
       {
          if ((data.errors)) {
            for(var error in data.errors)
            {
              $.notify(data.errors[error],"warn");
            }
          }
          else
          {
            $.notify(data.message,data.type);
          }
       }

      });
})

//==============CONTACT FORM END===========================

//**************************** GLOBAL CAPCHA****************************************

        $('.refresh_code').on( "click", function() {
            $.get($(this).data('href'), function(data, status){
                $('.codeimg').attr("src",""+mainurl+"/assets/images/capcha_code.png?time="+ Math.random());
            });
        })

    //**************************** GLOBAL CAPCHA ENDS****************************************



});

});