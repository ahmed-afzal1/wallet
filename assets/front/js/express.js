$(function ($) {
    "use strict";

    $(document).on('submit', '#payNow' ,function(e){
        $(".freeloader").fadeIn();

        var postData = $(this).serialize();
        var formURL = $(this).attr('action');

        $.ajax({
            url: formURL,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data)
            {
                setTimeout(function(){
                    if(data.status === "completed"){
                        $(".freeloader").fadeOut();
                        $("#e-payment").html(data.data);
                        setTimeout(function() {
                            window.location.href = data.redirect_url;
                        },5000);
                    }else{
                        $(".freeloader").fadeOut();
                        $("#resp").html(data.message);
                    }
                },1000);

            }
        });

        e.preventDefault();	//STOP default action
        e.unbind();
    });


});