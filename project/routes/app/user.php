<?php


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is all user web routes for this application.
|
*/


// Route::group(['prefix'=>'{locale}'],function(){
    // Route::get('/login','Userpanel\UsersController@showLoginForm')->name('login');

    Auth::routes();


    Route::get('/express/web/signin', 'Userpanel\ApiController@eXpressApiForm');
    Route::post('/express/web', 'Userpanel\ApiController@eXpressApi');
    Route::get('/express/web', 'Userpanel\ApiController@eXpressApi');
    Route::get('/express/web/pay', 'Userpanel\ApiController@eXpressApiForm');
    Route::post('/express/web/loginapi', 'Userpanel\ApiController@apiLogin')->name('api.login');
    Route::get('/express/web/loginapi', 'Userpanel\ApiController@apiLogin')->name('api.problem');

    Route::post('/express/web/paynow', 'Userpanel\ApiController@CompletePaymentApi')->name('api.payment');


    Route::group(['prefix'=>'user'],function(){
        Route::get('/register/type','Userpanel\UsersController@showRegisterTypeForm')->name('register.type');
        Route::get('/register','Userpanel\UsersController@showRegisterForm')->name('user.register.form');
        Route::post('/registration/{type}','Userpanel\UsersController@register')->name('user.register.submit');


        Route::get('/verify/{user}','Userpanel\UsersController@verify')->name('user-verify');
        Route::get('/resentverifylink','Userpanel\UsersController@resentverifylink')->name('resentverifylink');
        Route::get('/','Userpanel\UsersController@showLoginForm');
        Route::get('/login','Userpanel\UsersController@showLoginForm')->name('user.login');
        Route::post('/login','Userpanel\UsersController@login')->name('user.login.submit');
        Route::get('/forgot','Auth\ForgotController@showLoginForm')->name('user.forgot');
        Route::post('/forgot/submit','Auth\ForgotController@forgot')->name('user.forgot.submit');
        Route::get('/logout','Userpanel\UsersController@logout')->name('user.logout');

        Route::group(['middleware'=>'verified'],function(){

            Route::group(['profile'],function(){
                Route::get('/dashboard', 'Userpanel\DashboardController@index')->name('user-dashboard');
                Route::get('/profile', 'Userpanel\DashboardController@profile')->name('user-profile');
                Route::post('/changepassword', 'Userpanel\DashboardController@changepassword')->name('user-change-password');
                Route::get('/settings', 'Userpanel\UsersController@profileedit')->name('user-edit-profile');
                Route::post('/updateuserinfo', 'Userpanel\DashboardController@updateuserinfo')->name('user-info-update');
                Route::post('/userpersonalinfo', 'Userpanel\DashboardController@userpersonalinfo')->name('userpersonalinfo');
                Route::get('/setting', 'Userpanel\DashboardController@settingpageview')->name('user-account-setting-view');
                Route::post('/setting/update', 'Userpanel\DashboardController@setting')->name('user-account-setting');
                Route::get('/defaultcurrency', 'Userpanel\DashboardController@defaultcurrency')->name('user-set-default-currency');
            });


            Route::group(['Transaction'],function(){
                Route::get('send','Userpanel\TransactionController@sendformview')->name('user-send');
                Route::get('searchuser','Userpanel\TransactionController@searchuser')->name('user-search');
                Route::get('userexist','Userpanel\TransactionController@userexist')->name('user-exist');
                Route::get('translimit','Userpanel\TransactionController@translimit')->name('user-translimit');
                Route::get('transaction/all','Userpanel\TransactionController@alltransaction')->name('user-alltransaction');
                Route::get('transaction/details/','Userpanel\TransactionController@transactiondetails')->name('user-transaction-details');
                Route::get('transcost','Userpanel\TransactionController@transcost')->name('user-transcost');
                Route::post('sendmoney','Userpanel\TransactionController@create')->name('user-send-money');
                Route::post('sendmoney/refund/{id}','Userpanel\TransactionController@refundbalance')->name('user-refund-balance');
                // deposit routes 
                Route::get('deposit','Userpanel\TransactionController@depositformview')->name('user-deposit-money');

                Route::post('deposit/paypal/checkout/submit','Userpanel\PaypalController@store')->name('paypal.payment');
                Route::get('deposit/paypal/checkout/notify','Userpanel\PaypalController@notify')->name('paypal.notify');


                Route::post('/deposit/stripe/submit', 'Userpanel\StripeController@store')->name('stripe.submit');

                Route::post('/manual/submit', 'Userpanel\ManualController@store')->name('manual.submit');
                Route::get('/manual/notify', 'Userpanel\ManualController@notify')->name('manual.notify');


                // Molly Routes
                Route::post('/molly/submit', 'Userpanel\MollyController@store')->name('molly.submit');
                Route::get('/molly/notify', 'Userpanel\MollyController@notify')->name('molly.notify');


                //PayTM Routes
                Route::post('/paytm-submit', 'Userpanel\PaytmController@store')->name('paytm.submit');
                Route::post('/paytm-callback', 'Userpanel\PaytmController@paytmCallback')->name('paytm.notify');
                Route::post('/paystack/submit', 'Userpanel\PaystackController@store')->name('paystack.submit');


                Route::post('/coinpay-submit', 'Userpanel\CoinPaymentController@deposit')->name('coinpay.submit');
                Route::post('/coinpay/notify', 'Userpanel\CoinPaymentController@coincallback')->name('coinpay.notify');
                Route::get('/invest/coinpay', 'Userpanel\CoinPaymentController@blockInvest')->name('coinpay.invest');


                Route::post('/blockchain-submit', 'Userpanel\BlockChainController@deposit')->name('blockchain.submit');
                Route::post('/blockchain/notify', 'Userpanel\BlockChainController@chaincallback')->name('blockchain.notify');


                // Route::get('/ticket','Userpanel\TicketController@ticket')->name('user.ticket');


                Route::get('paymentfieldview/{type}','Userpanel\TransactionController@paymentfieldview')->name('user-paymentfieldview');
                Route::post('deposit-money','Userpanel\TransactionController@depositmoney')->name('user-deposit');
                // request routes 
                Route::get('requestmoney','Userpanel\TransactionController@requestformview')->name('user-request-money');
                Route::post('moneyrequest/send','Userpanel\TransactionController@createmoneyrequest')->name('user-request-money-create');
                Route::get('moneyrequest/received/','Userpanel\TransactionController@moneyrequestreceived')->name('user-request-received');
                Route::get('moneyrequest/show/{requestid}','Userpanel\TransactionController@showmoneyrequest')->name('user-money-request-show');
                Route::get('moneyrequest/delete/{requestid}','Userpanel\TransactionController@deletemoneyrequest')->name('user-money-request-delete');
                Route::post('moneyrequest/accept/{requestid}','Userpanel\TransactionController@acceptmoneyrequest')->name('user-money-request-accept');
                // withdraw routes
                Route::get('withdraw/','Userpanel\TransactionController@withdraw')->name('user-money-withdraw-page');
                Route::get('withdraw/balance/check','Userpanel\TransactionController@withdrawbalancecheck')->name('user-withdraw-amount-check');
                Route::post('withdraw/create','Userpanel\TransactionController@withdrawrequest')->name('user-withdraw-create');
                // refund route 
                // Route::post('refund/balance','Userpanel\TransactionController@refundbalance')->name('user-refund-balance');

                Route::get('withdraws','Userpanel\WithdrawController@withdraws')->name('user.withdraws.amount');
            
            });


            Route::group(['Exchange'],function(){
                Route::get('/exchange','Userpanel\ExchangeController@exchange')->name('user.exchange');
                Route::get('/from-wallet/{id}','Userpanel\ExchangeController@fromWallet')->name('from.wallet');
                Route::post('/money-exchange','Userpanel\ExchangeController@moneyexchange')->name('user.moneyexchange');
            });

            Route::group(['BankAccount'],function(){
                Route::get('bankaccounts','Userpanel\BankaccountsController@index')->name('user-bankaccount-create');
                Route::post('bankaccounts/store','Userpanel\BankaccountsController@store')->name('user-bankaccount-store');
                Route::get('bankaccounts/show','Userpanel\BankaccountsController@showbankaccounts')->name('user-bankaccounts-show');
                Route::get('bankaccounts/setprimary/{id}','Userpanel\BankaccountsController@bankaccountsetprimary')->name('user-bankaccount-set-primary');
                // Route::get('bankaccounts/delete/{id}','Userpanel\BankaccountsController@bankaccountdelete')->name('user-bankaccount-delete');
                
                Route::post('craditcard/store','Userpanel\BankaccountsController@craditcardstore')->name('user-craditcard-store');
                Route::get('craditcard/show','Userpanel\BankaccountsController@showcraditcards')->name('user-craditcard-show');
                Route::get('craditcard/setprimary/{id}','Userpanel\BankaccountsController@craditcardsetprimary')->name('user-craditcard-set-primary');
            });

            Route::group(['support'],function(){
                Route::get('support-ticket','Userpanel\SupportController@index')->name('user-support-ticket-create');
                Route::post('support-ticket/submit','Userpanel\SupportController@createticket')->name('user-support-ticket-submit');

                Route::get('support-ticket/{id}','Userpanel\SupportController@view')->name('user.support.ticket.view');
                Route::post('ticket/reply','Userpanel\SupportController@reply')->name('user.support.ticket.reply');

            });


         

        });

        Route::group(['Invoice'],function(){
            Route::get('/invoice', 'Userpanel\InvoiceController@invoice')->name('user.account.invoice');
            Route::post('/account/sendinvoice', 'Userpanel\InvoiceController@invoiceSend')->name('user.account.invoice.submit');


            Route::get('/invoicelist', 'Userpanel\InvoiceController@invoicelist')->name('user.account.invoicelist');
            Route::get('/invoice/link/details/{id}', 'Userpanel\InvoiceController@linkdetails')->name('user.account.linkdetails');
            Route::get('/invoice/link/', 'Userpanel\InvoiceController@linkPay')->name('user.account.link');
        });

        Route::post('/invoice/user','Userpanel\InvoiceController@login')->name('invoice.user.login');
        Route::post('/invoice/money/send','Userpanel\InvoiceController@invoiceMoney')->name('invoice.money.send');
    });
// });

