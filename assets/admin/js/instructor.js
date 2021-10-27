(function($) {
    "use strict";

  $(document).ready(function() {
// MESSAGE FORM

$(document).on('submit','#messageform',function(e){
  e.preventDefault();
  var href = $(this).data('href');
  $('.gocover').show();
  $('button.mybtn1').prop('disabled',true);
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
            $('#messageform textarea').val('');
          }
          else
          {
            $('.alert-danger').hide();
            $('.alert-success').show();
            $('.alert-success p').html(data);
            $('#messageform textarea').val('');
            $('#messages').load(href);
          }
          $('.gocover').hide();
          $('button.mybtn1').prop('disabled',false);
       }
      });
});

// MESSAGE FORM ENDS
  });

    // NORMAL FORM

    $(document).on('submit','.geniusform',function(e){

      var $this = $(this);

      e.preventDefault();
      if(admin_loader == 1)
      {
      $('.gocover').show();
      }

    $('#submit-btn').prop('disabled',true);
        $.ajax({
         method:"POST",
         url:$(this).prop('action'),
         data: new FormData(this),
         contentType: false,
         cache: false,
         processData: false,
         success:function(data)
         {
            if ((data.errors)) {
            $this.find('.alert-success').hide();
            $this.find('.alert-danger').show();
            $this.find('.alert-danger ul').html('');
              for(var error in data.errors)
              {
                $this.find('.alert-danger ul').append('<li>'+ data.errors[error] +'</li>')
              }
            }
            else
            {
              $this.find('.alert-danger').hide();
              $this.find('.alert-success').show();
              $this.find('.alert-success p').html(data);
		location.reload();


            }
            if(admin_loader == 1)
            {
                $('.gocover').hide();
            }

            $('#submit-btn').prop('disabled',false);
            
            $(window).scrollTop(0);

         }

        });

  });

  // NORMAL FORM ENDS

// DELETE OPERATION

$('#confirm-delete').on('show.bs.modal', function(e) {
  console.log('HI');
  $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#confirm-delete .btn-ok').on('click', function(e) {
if($('#confirm-delete .btn-ok').hasClass("order-btn")){
if(admin_loader == 1)
{
$('.submit-loader').show();
}
}
$.ajax({
 type:"GET",
 url:$(this).attr('href'),
 success:function(data)
 {
      $('#confirm-delete').modal('toggle');
      $('.alert-danger').hide();
      $('.alert-success').show();
      $('.alert-success p').html(data);

      if($('#confirm-delete .btn-ok').hasClass("order-btn")){
if(admin_loader == 1)
  {
    $('.submit-loader').hide();
  }
      }

 }
});
return false;
});

$('#confirm-delete1 .btn-ok').on('click', function(e) {

$.ajax({
 type:"GET",
 url:$(this).attr('href'),
 success:function(data)
 {
      $('#confirm-delete1').modal('toggle');
      $('.alert-danger').hide();
      $('.alert-success').show();
      $('.alert-success p').html(data[0]);
 }
});
return false;
});

// DELETE OPERATION END

})(jQuery);