(function($) {
    "use strict";



let accountlisturl = $('#accountlisturl').val();
let cardtlisturl = $('#cardtlisturl').val();



/*
|--------------------------------------------------------------------------
| Submit form data
|--------------------------------------------------------------------------
|
| This function only for Submit form data by ajax in anyware from this application
|
*/
$(document).on('submit','#formdatawithtable',function(e){
    e.preventDefault();
        $('#formdatawithtable button[type=submit]').parent().html(`<button class="btn btn-info" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading....
    </button>`);
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

            $('#formdatawithtable button').parent().html(`<button class="btn btn-warning" type="submit">
                Try Again!
            </button>`);
            
            }
            else
            {
            table.ajax.reload();
            $.notify(data,"success");
            $('#formdatawithtable button').parent().html(`<button class="btn btn-success" type="submit">
              Once More!
            </button>`);
            }
         }
  
        });
  });



$(document).on('submit','#formdata',function(e){
    e.preventDefault();
        $('#formdata button[type=submit]').parent().html(`<button class="btn btn-info" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading....
    </button>`);
    
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

            $('#formdata button').parent().html(`<button class="btn btn-warning" type="submit">
                Try Again!
            </button>`);
            
            }
            else
            {
            $.notify(data.message,data.type);
            if(data.type == 'success'){
                if(data.load != 'undefiend' || data.load != null && data.load == true){
                    window.location.reload();
                }
            $('#formdata button').parent().html(`<button class="btn btn-success" type="submit">
              Again!
            </button>`);
            }else{
                $('#formdata button').parent().html(`<button class="btn btn-warning" type="submit">
               Try Again!
              </button>`);
            }
            }
         }
  
        });
  });



$(document).on('submit','#requestformdata',function(e){
    e.preventDefault();
        $('#requestformdata button[type=submit]').parent().html(`<button class="btn btn-info" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading....
    </button>`);
    
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

            $('#requestformdata button').parent().html(`<button class="btn btn-warning" type="submit">
                Try Again!
            </button>`);
            
            }
            else
            {
            $.notify(data.message,data.type);
            if(data.type == 'success'){
            $('#requestformdata button').parent().html('');
            $('.pending').addClass('d-none');
            $('.completed').removeClass('d-none');
            }else{
                $('#requestformdata button').parent().html(`<button class="btn btn-warning" type="submit">
               Try Again!
              </button>`);
            }
            }
         }
  
        });
  });




  $(document).on('submit','#formdata2',function(e){
    e.preventDefault();
        $('.submit-loader').show();
        $('button[type=submit]').parent().html(`<button class="btn btn-info" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading....
    </button>`);
    
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
          $('.submit-loader').hide();
            if ((data.errors)) {
            
              for(var error in data.errors)
              {
                $.notify(data.errors[error],"warn");
              }

             $('#formdata2 button').parent().html(`<button class="btn btn-warning" type="submit">
                Try Again!
            </button>`);
            
            }
            else
            {
              $.notify(data.message,data.type);
              if(data.type == 'success'){
              $('#formdata2 button').parent().html(`<button class="btn btn-success" type="submit">
                Again!
              </button>`);
                  if(data.account != 'undefiend' || data.account != null && data.load == true){
                    getbankaccounts(accountlisturl);
                  }
                  if(data.card != 'undefiend' || data.card != null){
                    getcraditcards(cardtlisturl);
                  }
              }else{
                  $('#formdata2 button').parent().html(`<button class="btn btn-warning" type="submit">
                Try Again!
                </button>`);
              }
            }

         }
  
      });
  });



  $(document).on('submit', '#geniusformdata', function (e) {
    e.preventDefault();
  
    $('button.withdrawsubmitbtn').prop('disabled', true);
    $('.gocover').show();
  
    $.ajax({
      method: "POST",
      url: $(this).prop('action'),
      data: new FormData(this),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        if ((data.errors)) {
          $('.alert-success').hide();
          $('.alert-danger').show();
          $('.alert-danger ul').html('');
          for (var error in data.errors) {
            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
          }
          $('button.withdrawsubmitbtn').prop('disabled', false);
        }
        else {
          $('.alert-danger').hide();
          $('.alert-success').show();
          $('.alert-success p').html(data);
          $('button.withdrawsubmitbtn').prop('disabled', false);
        }
        $('body, html').animate({
          scrollTop: $('html').offset().top
        }, 'slow');
        $('.gocover').hide();

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
  });


   $(document).ready(function(){
    getbankaccounts(accountlisturl);
    getcraditcards(cardtlisturl);
   });
    
//    get all bank account from here 
    function getbankaccounts(route){
        $.get(route,function(e){
            $('.bank').remove();
            $('#bankaccountlist').before(e);
        });
    }
    
//    get all bank account from here 
    function getcraditcards(route){
        $.get(route,function(e){
            $('.cards').remove();
            $('#craditcardslist').before(e);
        });
    }



// delete data on click in datatable
$(document).on('click','.setinfopath',function () {
    var confirm = $(this).attr('data-href');
    $('.setinformationprimary').attr('data-href',confirm);
  });
  


// set account Primary in this function
$(document).on('click','.setinformationprimary',function () {
    let dataname = $(this).attr('data-name');
    $.ajax({
        type:"GET",
        url:$(this).attr('data-href'),
        success:function(data)
        {
            if(dataname == 'bankinfo'){
                console.log('bank');
                $('#setprimaryaccount').modal('toggle');
                $.notify(data.message ,data.type);
                getbankaccounts(accountlisturl);
            }
            if(dataname == 'cardinfo'){
                console.log('card');
                $('#setprimaryCard').modal('toggle');
                $.notify(data.message ,data.type);
                getcraditcards(cardtlisturl);
            }
        }
     });
});




// set account Primary in this function
$(document).on('click','.infonotapproved',function () {
    $.notify('Information isn\'t approved!' ,'warn');
});






$('#catalog-modal').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});



// delete data on click in datatable
$(document).on('click','.confirm-delete',function () {
  var confirm = $(this).attr('data-href');
  $('#confirm-delete').find('.ok-delete').attr('data-href',confirm);
  $('#confirm-delete').find('.data-delete').attr('href',confirm);
});






// delete data on click in datatable
$(document).on('click','.ok-delete',function () {
  $.ajax({
    type:"GET",
    url:$(this).attr('data-href'),
    success:function(data)
    {
        $('#confirm-delete').modal('toggle');
        $.notify(data.message ,data.type);
        table.ajax.reload();
    }
   });
});









// delete data on click in datatable
$(document).on('click','.menu-list a',function () {
    let path = $(this).attr('htef');
    let name = $(this).attr('data-name');
    let id = $(this).attr('data-id');
    $('#editmenu form').attr('action',path);
    $('.editmenuname').val(name);
    $('.editmenuid').val(id);
});




/*
|--------------------------------------------------------------------------
| Clear field data
|--------------------------------------------------------------------------
|
| This function only for clear all input,select,textarea field when modal close
|
*/

$('.modal').on('hidden.bs.modal', function (e) {
    $(this)
      .find("input,textarea,select")
         .val('')
         .end()
      .find("input[type=checkbox], input[type=radio]")
         .prop("checked", "")
         .end();
})






/*
|--------------------------------------------------------------------------
| text field validation
|--------------------------------------------------------------------------
|
| This method only for text field validation for integer number
|
*/
$(".allowIntiger").on("keypress keyup blur",function (event) {    
  $(this).val($(this).val().replace(/[^\d].+/, ""));
   if ((event.which < 48 || event.which > 57)) {
       event.preventDefault();
   }
});







/*
|--------------------------------------------------------------------------
| text field validation
|--------------------------------------------------------------------------
|
| This method only for text field validation for decemal number
|
*/
$(".allownDecimal").on("keypress keyup blur",function (event) {
   $(this).val($(this).val().replace(/[^0-9\.]/g,''));
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});






/*
|--------------------------------------------------------------------------
|  Chosen image preview 
|--------------------------------------------------------------------------
|
| This method only for  preview chosen image
|
*/
function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        console.log(e.target.result);
        $('#image-preview').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]);
    }
}

$("#image-upload").change(function() {
    readURL(this);
});




/*
|--------------------------------------------------------------------------
|  Chosen role menu 
|--------------------------------------------------------------------------
|
| This method only for select role menu box  
*/
$(document).on('click','.checkbox input',function(){
     let x = $(this).attr('id');
     let z = 'label[for="'+x+'"]';
     $(z).toggleClass('bg-success text-white');
});

$('.submit-loader').hide();
$('.submit-loader').parent().css('position','relative');



$(document).on('change','#paymentmethod',function(){
  $('.submit-loader').show();
  let url = $("#paymentmethod option:selected").attr('data-route');
  console.log(url);
  $.get(url,function(e){
   $('#methodinputbox').html(e);
   $('.submit-loader').hide();
  });
});

	// Checkout
  $(document).on('change','#paymentmethod1',function(){
    var val = $(this).val();
    
    if (val == 'Stripe') {
      $(document).on('click','.card-check',function(){
        var cardValue = $(this).val();
        if(cardValue == 0){
          $('.card-show').removeClass('d-none');
          $('.card-elements').prop('required', true);
        }else{
          $('.card-show').addClass('d-none');
          $get()
          $('.card-elements').prop('required',false);
        }
      })
      $('.transaction_number_area').addClass('d-none');
      $('#transaction_number').prop('required',false);

      $('#deposit_form').prop('action', $('#stripe_route').val());
      $('#deposit_form').prop('class','');

      $('.string-show').removeClass('d-none');
      $('.offline-show').addClass('d-none');
    } 
    else if (val == 'Paypal') {
      $('#deposit_form').prop('action',$('#paypal_route').val());
      $('#deposit_form').prop('class','');

      $('.transaction_number_area').addClass('d-none');
      $('#transaction_number').prop('required',false);


      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
    else if (val == 'Mollie Payment') {
      $('#deposit_form').prop('action',$('#molly_route').val());
      $('#deposit_form').prop('class','');
      $('.transaction_number_area').addClass('d-none');
      $('#transaction_number').prop('required',false);


      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
    else if (val == 'Paytm') {
      $('#deposit_form').prop('action',$('#paytm_route').val());
      $('#deposit_form').prop('class','');
      $('.transaction_number_area').addClass('d-none');
      $('#transaction_number').prop('required',false);

      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
    else if (val == 'BlockChain') {
      $('#deposit_form').prop('action',$('#blockchain_route').val());
      $('#deposit_form').prop('class','');
      $('.transaction_number_area').addClass('d-none');
      $('#transaction_number').prop('required',false);

      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
    else if (val == 'Manual') {
      $('#deposit_form').prop('action',$('#manual_route').val());
      $('#deposit_form').prop('class','');
      $('.transaction_number_area').removeClass('d-none');
      $('#transaction_number').prop('required',true);

      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
    else{
      $('#deposit_form').prop('class','step1-form');
      $('#deposit_form').prop('action',$('#paystack_route').val());

      $('.string-show').addClass('d-none');
      $('.offline-show').addClass('d-none');
      $('.card-elements').prop('required',false);
    }
});


$(document).on('submit','.step1-form',function(){
    var val = $('#sub').val();
    var total = $('#amount').val();
    var paystackInfo = $('#paystackInfo').val();
    var curr = $('#currency option:selected').text();
    total = Math.round(total);
        if(val == 0)
        {
        var handler = PaystackPop.setup({
          key: paystackInfo,
          email: $('input[name=email]').val(),
          amount: total * 100,
          currency: curr,
          ref: ''+Math.floor((Math.random() * 1000000000) + 1),
          callback: function(response){
            $('#ref_id').val(response.reference);
            $('#sub').val('1');
            $('#final-btn').click();
          },
          onClose: function(){
            window.location.reload();
          }
        });
        handler.openIframe();
            return false;                    
        }
        else {
          $('#preloader').show();
            return true;   
        }
});

$(document).on('change','#withdraw-method',function(){
  var val = $(this).val();

  if(val == 'Bank'){
    $(document).on('click','.bank-check',function(){
      var bankValue = $(this).val();
      if(bankValue == 0){
        $('.bank-show').removeClass('d-none');
        $('.withdraw-require').prop('required', true);
      }else{
        $('.bank-show').addClass('d-none');
        $('.withdraw-require').prop('required',false);
      }
    })
    $('.userEmail').prop('required',false)
    $('.bank-section').removeClass('d-none');
    $('.mail-section').addClass('d-none'); 
  }else{
    $('.bank-section').addClass('d-none');
    $('.mail-section').removeClass('d-none');
    $('.userEmail').prop('required',true)
    $('.withdraw-require').prop('required',false);
  }

})


$(document).on('keyup','#transamount',function(){
  $('#inputamount').text('');

    let minamount = $(".sendcurrency option:selected").attr('data-minvalue');
    let maxamount = $(".sendcurrency option:selected").attr('data-maxvalue');
    let amount = $(this).val()

    if(parseFloat(minamount) > parseFloat(amount) || parseFloat(maxamount) < parseFloat(amount)){
      $('#inputamount').text('Minimum Amount '+minamount+' Maximum Amount '+maxamount);
      $('.sendbtn').prop('disabled',true);
      
    }else{
      $('.sendbtn').prop('disabled',false);
    }
    
})

})(jQuery);