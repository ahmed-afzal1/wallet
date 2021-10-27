(function($) {
    "use strict";

    /*
    |
    | This function is for by default hide the submit button. 
    | and display the message for fill input fields
    |
    */
    $(document).ready(function () {

        let senddercurrencycode = 0;
        let availablebalance = 0;
        let transamount = 0;
        let sendTo = 0;
        let usertransaction = [];
        let mintransaction =  0;
        let translimitamount = 0;
        let transactioncost = 0;
        let reqamount = 0;
        let trans_cost_route = $('#trans_cost_route').attr('data-url');
        let requestcurrencycode = $('#requestcurrencycode').val();
        let page = $('#page').val();
     
        $('#insufficient').hide();
    
        $(document).on('change','#currencycode',function(){
            mintransaction =  $('#transamount').attr('min');
            $('#maxtranslimit').html("");
            
            availablebalance = parseFloat($('#currencycode option:selected').attr('data-balance'));
            senddercurrencycode = $('#currencycode option:selected').attr('data-currency');
    
            setTimeout(function(){
                transcost();
            },800);
          
        });
        
        if(page == 'send'){
            senddercurrencycode = $('#currencycode option:selected').attr('data-currency');
            availablebalance = parseFloat($('#currencycode option:selected').attr('data-balance'));
            transamount = parseFloat($('#transamount').val());
            $('.submittransectionerror').show();
            $('#totalbalance').hide();
            translimit();
            transcost();
            // balanceConvert();
            submittranction();
        }
        if(page != 'withwraw'){
            $('#reqtransamount').val($('#requestcurrencycode option:selected').attr('data-minamount'));
            requestamount();
        }
        if(page == 'withwraw'){
            let amount= $('#withdrawamount').val();
            let currency = $('#withdrawcurrency option:selected').val();
            let minamount= $('#withdrawcurrency option:selected').attr('data-inrate');
            let maxamount= $('#withdrawcurrency option:selected').attr('data-inratemaxamount');
            checkwithwrawbalance(amount,currency);
            
            let sign= $('#withdrawcurrency option:selected').attr('data-sign');

            $('#minwithdrawamount').html('Minimum withdrawal amount is ' + sign +minamount);
            $('#maxwithdrawamount').html('Maximum withdrawal amount is ' + sign +maxamount);
        }
    });


    // function balanceConvert(balance = availablebalance,currency=senddercurrencycode, changecurrency = null){
    //     let conv_bal_url = $('#currencycode').attr('data-cburl');
    //     conv_bal_url = conv_bal_url + "?balance="+balance+"&&currency="+currency;
    //     // alert(conv_bal_url);
    //     $.ajax({
    //         method : 'get',
    //         url: conv_bal_url,
    //         success: function(response){
    //             if(changecurrency == true){
    //                 $('#transamount').val(response.mintransaction);
    //             }
    //             $('#transamount').attr('min',response.mintransaction);
    //             $('#transamount').attr('max',response.maxtransaction);
    //             $('#mintransamount').html(response.mintransactionwithsign);
    //             $('#maxtransamount').html(response.maxtransactionwithsign);
    //         }
    //     });



    //     setTimeout(function(){
    //         translimitamount = parseFloat(parseFloat(usertransaction.transaction_total).toFixed(2));
    //         if(parseFloat(usertransaction.max_transaction_amount).toFixed(2) < translimitamount){
    //             $('#maxtranslimit').removeClass('d-none');
    //             // $('.submittransection').addClass('d-none');
    //         }else{
    //             $('#maxtranslimit').addClass('d-none');
    //             // $('.submittransection').removeClass('d-none');
    //         }
    //         submittranction();
    //     }, 500);
        
    // }



   







    /*
    |
    | This function is for toggle the submit button by field value.
    | if any mandatory field is empty then the submit button will hide otherwise it's displyed
    | its also display a warning message for wrong data else hide
    |
    */
    function submittranction(){
        transamount = parseFloat($("#transamount").val());
        mintransaction =  parseFloat($('#currencycode option:selected').attr('data-minvalue'));
        let maxtransaction =  parseFloat($('#transamount').attr('max'));

        if(mintransaction <= transamount && maxtransaction >= transamount && availablebalance >= transamount){
            if(sendTo == 1){
                $('.submittransection').removeClass('d-none');
                $('.submittransectionerror').slideUp();
            }
            $('#insufficient').hide();
        }else{   
            $('.submittransection').addClass('d-none');
            $('.submittransectionerror').slideDown();
            $('#insufficient').show();
        }
    }









    /*
    |
    | This function is for check transaction amount lowerden or equal the minimum amount or not,
    | if lowerden or equal then system will ready to submit the transaction, with confirmation or warning message 
    | else the given amount in the field is the transaction amount.
    | after done everything then show a message to submit transsaction.
    |
   */
    $(document).on('keyup keydown','#transamount',function(){
        transamount = parseFloat($(this).val());
        if(transamount > 0 && transamount != null && transamount != ''){
            balancecheck();
            translimit();
        }
        
        transcost();
        // balanceConvert();
    });








    /*
    | check the transaction balance uper or lower is here 
    | this function is called in another functions inside
    */ 
    function balancecheck() {
            if(transamount > 0 && availablebalance >= transamount){
                $("#balanceout").html('');
            }else{
                $("#balanceout").html('<span  class="text-danger">Out of balance</span>');
            }
    }





    /*
    |
    | This function is for check transaction amount lowerden or equal the minimum amount or not,
    | if lowerden then automaticaly change the amount by the minimum amount,
    | else the given amount in the field is the transaction amount.
    | after all of the work done then transaction cost calulation function is recalled
    | for the proper cost. 
    |
   */
    $(document).on('blur','#transamount',function(){
        let mintrancamount = parseFloat($(this).attr('min'));
        let maxtransamount = parseFloat($(this).attr('max'));
        let transamount = parseFloat($(this).val());
        if(transamount < mintrancamount || isNaN(transamount)){
            $(this).val(mintrancamount);
        }
        if(transamount > maxtransamount || isNaN(transamount)){
            $(this).val(maxtransamount);
        }
        translimit();
        // balanceConvert();
        setTimeout(function(){
            transcost();
        },1000);
    });

    function translimit(){
        let daily_trans_limit_route = $('#daily_trans_limit_route').val();
        $.get(daily_trans_limit_route+"?currency="+senddercurrencycode+"&&transamount="+transamount,function(response){
            usertransaction = response;
        });
    }
    
    
    function transcost(){
        let trans_cost_route = $('#trans_cost_route').attr('data-url');
        $.get(trans_cost_route+"?transamount="+transamount+"&&currency="+senddercurrencycode,function(response){
          $('#transcost').html(response.balancewithsign);
          $('#trans_cost_route').val(response.balance);
          $('#totalbalance strong').html(response.withcostamountsign);
         transactioncost = response.balance;
        });
    }
    
    
    $(document).on('click','.paycost',function () {
        if($(this).prop("checked")==true){

            let inputAmount = $("#transamount").val();
            let fixCharge = $(".sendcurrency option:selected").attr('data-fixCharge');
            let percentCharge = $(".sendcurrency option:selected").attr('data-percentCharge');
            let Cost = parseFloat(fixCharge)+(parseFloat(inputAmount)/100)*parseFloat(percentCharge);
            let totalCost = Cost ? Cost : 0;
            $('#totalbalance').show().text(totalCost);

        }else{
            $('#totalbalance').hide();
        }
    });



    
    /*
    |
    | This function is for search an user in users table by the keyup in #searchuser 
    | input field using the route is {user-search} from transaction table if user 
    | create a transaction previeusly if transaction table is empty then its call user table 
    | it's return data without users self information
    |
   */

    $(document).on('keyup paste','#searchuser',function(){
    var useremail = $(this).val();
    let dataurl = $(this).attr('data-href'); 
    var dataimage = $("#userimage").attr('data-src'); 
        dataurl = dataurl + '?user=' + useremail; 
        if(useremail != null){
            $.ajax({
                method:'get',
                url: dataurl,
                success: function(response){
                    $('#useridentity').slideUp();
                    if(response.length != undefined){
                        $('#searchuserdata').html(response);
                    }else{
                        $('#searchuserdata').html('');
                        $('#useridentity').slideUp();
                        $('#userimage img').attr('src',dataimage);
                    }

                    if(response.single == true){
                        $('#searchuserdata').html(response.data);
                        $('#searchuser').val(response.email);
                        $('#useridentity span').html(response.name);
                        $('#useridentity').slideDown();
                        $('#userimage img').attr('src',response.image);
                    }
                }
            });
        }else{
            $('#useridentity').slideUp();
            $('#userimage img').attr('src',dataimage);
        }
    });

    // search user by input field blur
    $(document).on('blur','#searchuser',function(){
        sendTo = 0;
        setTimeout(function(){ 
            var useremail = $('#searchuser').val();
            var dataurl = $('#searchuser').attr('data-exist'); 
            var dataimage = $("#userimage").attr('data-src'); 
            dataurl = dataurl + '?email=' + useremail; 
            if(useremail != null){
                $.ajax({
                    method:'get',
                    url: dataurl,
                    success: function(response){
                        sendTo = 1;
                        $("#usernotfound").removeClass('d-none');
                        if(response == 'false'){
                            sendTo = 0;
                            $('#userimage img').attr('src',dataimage);
                            $("#usernotfound").html('<span  class="text-danger">Invalid user!</span>');
                            $('.sendbtn').prop('disabled',true)
                        }else{
                            
                            $("#usernotfound").html('<span  class="text-success">User available!</span>');
                            $('.sendbtn').prop('disabled',false)
                        }
                        submittranction();
                    }
                });
            }
        }, 500);
        
    });

    $(window).click(function() {
        $('#searchuserdata').html('');
    });

    $('#useridentity').hide();
    $(document).on('click','.useremail',function(){
        $('#searchuser').val($(this).attr('data-email'));
        $('#useridentity span').html($(this).attr('data-name'));
        $("#usernotfound").html('<span  class="text-success">User Available.</span>');
        $('#useridentity').slideDown();
        $('#userimage img').attr('src',$(this).attr('data-image'));
        $('#searchuserdata').html('');
    });







    var minamount  =  $('#requestcurrencycode option:selected').attr('data-minamount');
    var maxamount  =  $('#requestcurrencycode option:selected').attr('data-maxamount');
    /* request money calculation*/
        $(document).on('change','#requestcurrencycode',function(){
            requestcurrencycode = $(this).val();
            reqamount = $('#requestcurrencycode option:selected').attr('data-minamount');
            minamount  =  $('#requestcurrencycode option:selected').attr('data-minamount');
            maxamount  =  $('#requestcurrencycode option:selected').attr('data-maxamount');
            $('#reqtransamount').val(reqamount);
            requestamount();
        
        });
        
        function requestamount() { 
            $.get(trans_cost_route+"?transamount="+reqamount+"&&currency="+requestcurrencycode,function(response){
                console.log(requestcurrencycode);
                $('#transcost').html(response.balancewithsign);
                $('#trans_cost_route').val(response.balance);
                $('#totalbalance strong').html(response.withcostamountsign);
            });
        }

        // $(document).on('keyup keydown','#reqtransamount',function () {
        //     var reqamount = $(this).val();
        //     requestamount();
        //     requesttranslimit();
        // });

        $(document).on('blur','#reqtransamount',function () {
            minamount = parseFloat(minamount);
            maxamount = parseFloat(maxamount);
            reqamount = parseFloat(reqamount);
            alert();
            if(maxamount < reqamount || isNaN(reqamount)){
                $(this).val(maxamount);
            }
            if(minamount > reqamount || isNaN(reqamount)){
                $(this).val(minamount);
            }
            reqamount = $(this).val();
            requestamount();
            requesttranslimit();
        });



        function requesttranslimit(){

            minamount = parseFloat(minamount);
            maxamount = parseFloat(maxamount);
            reqamount = parseFloat(reqamount);

            if(maxamount < reqamount){
                $('#balanceout').html('Out of max limit!');
                $('.request-btn').slideUp();
            }else if(minamount > reqamount){
                $('.request-btn').slideUp();
                $('#balanceout').html('Out of min limit!');
            }else{
                $('.request-btn').slideDown();
                $('#balanceout').html('');
            }
        
        }



    // check withdrawal balance Insufficient account balance or not 
    $(document).on('keyup paste','#withdrawamount',function(){
        let amount= $(this).val();
        let currency = $('#withdrawcurrency option:selected').val();
        checkwithwrawbalance(amount,currency);
        withdrawamountrange(amount);

        let fixCharge = parseFloat($('#withdrawcurrency option:selected').attr('data-fixedCharge'));
        let percentageCharge = parseFloat($('#withdrawcurrency option:selected').attr('data-percentageCharge'));
        let transactionTotal = (amount/100)*percentageCharge;
        let transactionTotalCost = fixCharge + transactionTotal;

        $('#transactioncost').html(transactionTotalCost);
    });


    function withdrawamountrange(amount=null,keyevent = null){
        let minamount= $('#withdrawcurrency option:selected').attr('data-inrate');
        let maxamount= parseFloat($('#withdrawcurrency option:selected').attr('data-inratemaxamount'));
        if( parseFloat(maxamount) < parseFloat(amount)){
            let maxamountwithsign = $('#withdrawcurrency option:selected').attr('data-maxsign');
            $('#withdrawminamount').html('Maximum withdraw amount '+ maxamountwithsign);
            $('#balancemsg').html('Maximum withdraw amount exceed');
            $('#withdrawsubmitbtn').hide();
            if(keyevent == 'blur'){   
                $('#withdrawamount').val(maxamount);
                $('#withdrawsubmitbtn').show();
                $('#balancemsg').html('');
                $('#withdrawminamount').html('');
            }
        }else if( parseFloat(amount) < parseFloat(minamount) || isNaN(amount) || amount == ''){
            let minamountwithsign= $('#withdrawcurrency option:selected').attr('data-sign');
            $('#withdrawminamount').html('Minimum withdraw amount '+ minamountwithsign);
            $('#balancemsg').html('Minimum withdraw amount '+ minamountwithsign);
            $('#withdrawsubmitbtn').hide();
            if(keyevent == 'blur'){   
                $('#withdrawamount').val(minamount);
                $('#withdrawsubmitbtn').show();
                $('#balancemsg').html('');
                $('#withdrawminamount').html('');
            }
        }else{
            $('#withdrawminamount').html('');
            $('#balancemsg').html('');
            $('#withdrawsubmitbtn').show();
        }

        
    }


    // check withdrawal balance Insufficient account balance or not 
    $(document).on('change','#withdrawcurrency',function(){
        let transaction_cost = 0;
        let minamount= $('#withdrawcurrency option:selected').attr('data-inrate');
        let maxamount= $('#withdrawcurrency option:selected').attr('data-inratemaxamount');
        let currency = $('#withdrawcurrency option:selected').val();
        let fixedCharge = parseFloat($('#withdrawcurrency option:selected').attr('data-fixedCharge'));
        let percentageCharge = $('#withdrawcurrency option:selected').attr('data-percentageCharge');

        let sign= $('#withdrawcurrency option:selected').attr('data-sign');

        $('#minwithdrawamount').html('Minimum withdrawal amount is ' + sign + minamount);
        $('#maxwithdrawamount').html('Maximum withdrawal amount is ' + sign + maxamount);

        let transactionAmount = parseFloat((minamount/100)*percentageCharge);
        let transactionCost = fixedCharge + transactionAmount;

        $('#withdrawamount').val(minamount);
        $('#transactioncost').html(transactionCost);
        // $('.submit-loader').show();
        setTimeout(function(){
            let amount= $('#withdrawamount').val();
            checkwithwrawbalance(amount,currency);
        },800);

    });

    $(document).on('blur','#withdrawamount',function(){
        let minamount= $('#withdrawcurrency option:selected').attr('data-inrate');
        let currency = $('#withdrawcurrency option:selected').val();
        $('.submit-loader').show();
        setTimeout(function(){
            let amount= $('#withdrawamount').val();
            withdrawamountrange(amount,'blur');
            checkwithwrawbalance(amount,currency);
        },800);

    });


    function checkwithwrawbalance(amount,currency) {
        $('.submit-loader').show();
        let withdrawurl = $('#withdrawcurrency').attr('data-route');
        $.get(withdrawurl+"?amount="+amount+"&currency="+currency,function(e){
            $('#transactioncost').html(e.balancewithsign);
            $('.submit-loader').hide();
        });
    }
    

    





})(jQuery);