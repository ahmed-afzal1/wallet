<?php


    /*
    |--------------------------------------------------------------------------
    | Admin basic Routes
    |--------------------------------------------------------------------------
    |
    | Here is all basic routes for admin.
    |
    */




Route::redirect('admin', 'admin/login');

Route::prefix('admin')->group(function(){


        Route::get('/login','Backend\AdminController@showLoginForm')->name('admin.login');
        Route::post('/login','Backend\AdminController@login')->name('admin.login.submit');
        // Forgot Routes
        Route::get('/forgot', 'Backend\AdminController@showForgotForm')->name('admin.forgot');
        Route::post('/forgot', 'Backend\AdminController@forgot')->name('admin.forgot.submit');
        Route::get('/balanceconvert', 'ToolsController@balanceconvert')->name('balanceconvert');
        Route::get('/dashboard', 'Backend\DashboardController@index')->name('admin.dashboard');


        Route::group(['middleware'=>'permissions:Manage Blog'],function(){
            //------------ ADMIN BLOG SECTION ------------
              Route::get('/blog/datatables', 'Backend\BlogController@datatables')->name('admin-blog-datatables'); //JSON REQUEST
              Route::get('/blog', 'Backend\BlogController@index')->name('admin-blog-index');
              Route::get('/blog/create', 'Backend\BlogController@create')->name('admin-blog-create');
              Route::post('/blog/create', 'Backend\BlogController@store')->name('admin-blog-store');
              Route::get('/blog/edit/{id}', 'Backend\BlogController@edit')->name('admin-blog-edit');
              Route::post('/blog/edit/{id}', 'Backend\BlogController@update')->name('admin-blog-update');
              Route::get('/blog/delete/{id}', 'Backend\BlogController@destroy')->name('admin-blog-delete');
      
              Route::get('/blog/category/datatables', 'Backend\BlogCategoryController@datatables')->name('admin-cblog-datatables'); //JSON REQUEST
              Route::get('/blog/category', 'Backend\BlogCategoryController@index')->name('admin-cblog-index');
              Route::get('/blog/category/create', 'Backend\BlogCategoryController@create')->name('admin-cblog-create');
              Route::post('/blog/category/create', 'Backend\BlogCategoryController@store')->name('admin-cblog-store');
              Route::get('/blog/category/edit/{id}', 'Backend\BlogCategoryController@edit')->name('admin-cblog-edit');
              Route::post('/blog/category/edit/{id}', 'Backend\BlogCategoryController@update')->name('admin-cblog-update');
              Route::get('/blog/category/delete/{id}', 'Backend\BlogCategoryController@destroy')->name('admin-cblog-delete');
            //------------ ADMIN BLOG SECTION ENDS ------------
        });

        Route::group(['middleware'=>'permissions:Home Page Settings'],function(){

            //-------------SLIDER SECTION -------------------
            Route::get('/slider/datatables', 'Backend\SliderController@datatables')->name('admin.slider.datatables');
            Route::get('/slider', 'Backend\SliderController@index')->name('admin.slider');           // Admin Dashboard route
            Route::get('/slider/create', 'Backend\SliderController@create')->name('admin.slider.create');
            Route::post('/slider/store', 'Backend\SliderController@store')->name('admin-sl-store');
            Route::get('/slider/edit/{id}', 'Backend\SliderController@edit')->name('admin-sl-edit');
            Route::post('/slider/edit/{id}', 'Backend\SliderController@update')->name('admin-sl-update');
            Route::get('/slider/delete/{id}', 'Backend\SliderController@destroy')->name('admin-sl-delete');
            //-------------SLIDER SECTION ENDS-------------------


            //-------------SERVICE SECTION -------------------
            Route::get('/service/datatables', 'Backend\ServiceController@datatables')->name('admin-service-datatables');
            Route::get('/service', 'Backend\ServiceController@index')->name('admin-service-index');
            Route::get('/service/create', 'Backend\ServiceController@create')->name('admin-service-create');
            Route::post('/service/create', 'Backend\ServiceController@store')->name('admin-service-store');
            Route::get('/service/edit/{id}', 'Backend\ServiceController@edit')->name('admin-service-edit');
            Route::post('/service/edit/{id}', 'Backend\ServiceController@update')->name('admin-service-update');
            Route::get('/service/delete/{id}', 'Backend\ServiceController@destroy')->name('admin-service-delete');
            //-------------SERVICE SECTION ENDS-------------------

            //-------------PROJECT SECTION -------------------
            Route::get('/project/datatables', 'Backend\PortfolioController@datatables')->name('admin-portfolio-datatables');
            Route::get('/projects', 'Backend\PortfolioController@index')->name('admin-portfolio-index');
            Route::get('/projects/create', 'Backend\PortfolioController@create')->name('admin-portfolio-create');
            Route::post('/projects/create', 'Backend\PortfolioController@store')->name('admin-portfolio-store');
            Route::get('/projects/edit/{id}', 'Backend\PortfolioController@edit')->name('admin-portfolio-edit');
            Route::post('/projects/edit/{id}', 'Backend\PortfolioController@update')->name('admin-portfolio-update');
            Route::get('/projects/delete/{id}', 'Backend\PortfolioController@destroy')->name('admin-portfolio-delete');
            //-------------PROJECT SECTION ENDS-------------------


            //-------------REVIEW SECTION -------------------
            Route::get('/review/datatables', 'Backend\ReviewController@datatables')->name('admin-review-datatables'); //JSON REQUEST
            Route::get('/review', 'Backend\ReviewController@index')->name('admin-review-index');
            Route::get('/review/create', 'Backend\ReviewController@create')->name('admin-review-create');
            Route::post('/review/create', 'Backend\ReviewController@store')->name('admin-review-store');
            Route::get('/review/edit/{id}', 'Backend\ReviewController@edit')->name('admin-review-edit');
            Route::post('/review/edit/{id}', 'Backend\ReviewController@update')->name('admin-review-update');
            Route::get('/review/delete/{id}', 'Backend\ReviewController@destroy')->name('admin-review-delete');
            //-------------REVIEW SECTION ENDS-------------------

            Route::get('/page-settings/customize', 'Backend\PageSettingController@customize')->name('admin-ps-customize');
            Route::get('/page-settings/homecontact', 'Backend\PageSettingController@homecontact')->name('admin-ps-homecontact');

        });

        Route::group(['middleware'=>'permissions:Menu Page Settings'],function(){
            //-------------FAQ SECTION -------------------
            Route::get('/faq/datatables', 'Backend\FaqController@datatables')->name('admin-faq-datatables');
            Route::get('/admin-faq', 'Backend\FaqController@index')->name('admin-faq-index');
            Route::get('/faq/create', 'Backend\FaqController@create')->name('admin-faq-create');
            Route::get('/faq/edit/{id}', 'Backend\FaqController@edit')->name('admin-faq-edit');
            Route::get('/faq/delete/{id}', 'Backend\FaqController@destroy')->name('admin-faq-delete');
            Route::post('/faq/update/{id}', 'Backend\FaqController@update')->name('admin-faq-update');
            Route::post('/faq/create', 'Backend\FaqController@store')->name('admin-faq-store');
            //-------------FAQ SECTION ENDS-------------------

            //-------------PAGE SECTION -------------------
            Route::get('/page/datatables', 'Backend\PageController@datatables')->name('admin-page-datatables');
            Route::get('/page', 'Backend\PageController@index')->name('admin-page-index');
            Route::get('/page/create', 'Backend\PageController@create')->name('admin-page-create');
            Route::get('/page/edit/{id}', 'Backend\PageController@edit')->name('admin-page-edit');
            Route::post('/page/update/{id}', 'Backend\PageController@update')->name('admin-page-update');
            Route::get('/page/delete/{id}', 'Backend\PageController@destroy')->name('admin-page-delete');
            Route::get('/page/status/{id1}/{id2}', 'Backend\PageController@status')->name('admin-page-status');
            Route::post('/page/create', 'Backend\PageController@store')->name('admin-page-store');
            //-------------PAGE SECTION ENDS-------------------


            Route::get('/page-settings/contact', 'Backend\PageSettingController@contact')->name('admin-ps-contact');
            Route::get('/page-settings/experience', 'Backend\PageSettingController@video')->name('admin-ps-video');
            Route::get('/page-settings/homecontact', 'Backend\PageSettingController@homecontact')->name('admin-ps-homecontact');
            Route::get('/page-settings/present', 'Backend\PageSettingController@present')->name('admin-ps-present');
            Route::get('/page-settings/blog', 'Backend\PageSettingController@blog')->name('admin-ps-blog');
            Route::post('/page-settings/update/all', 'Backend\PageSettingController@update')->name('admin-ps-update');
            Route::post('/page-settings/update/home', 'Backend\PageSettingController@homeupdate')->name('admin-ps-homeupdate');
            Route::get('/general-settings/status/{field}/{status}', 'Backend\SettingsController@status')->name('admin-gs-status');

        });



    Route::group(['profile'],function(){
        Route::get('/logout', 'Backend\AdminController@logout')->name('admin.logout');                      // Logout Route
        Route::get('/myprofile', 'Backend\DashboardController@profile')->name('admin.myprofile');           // Profile related routes
        Route::get('/updatestatus/{id1}/{id2}', 'Backend\StaffController@updatestatus')->name('admin.updatestatus');    // admin status update
        Route::get('/permitless', 'Backend\DashboardController@permitless')->name('admin.permitless');    // admin status update
        Route::post('/changepassword/{id}', 'Backend\DashboardController@changepassword')->name('admin-changepassword');    // admin password update
    });


    Route::group(['middleware' => 'permissions:Payment Settings'], function () {
        Route::get('/paymentgateway/datatables', 'Backend\PaymentGatewayController@datatables')->name('admin.payment.datatables'); //JSON REQUEST
        Route::get('/paymentgateway', 'Backend\PaymentGatewayController@index')->name('admin.payment.index');
        Route::get('/paymentgateway/create', 'Backend\PaymentGatewayController@create')->name('admin.payment.create');
        Route::post('/paymentgateway/create', 'Backend\PaymentGatewayController@store')->name('admin.payment.store');
        Route::get('/paymentgateway/edit/{id}', 'Backend\PaymentGatewayController@edit')->name('admin.payment.edit');
        Route::post('/paymentgateway/update/{id}', 'Backend\PaymentGatewayController@update')->name('admin.payment.update');
        Route::get('/paymentgateway/delete/{id}', 'Backend\PaymentGatewayController@destroy')->name('admin.payment.delete');
        Route::get('/paymentgateway/status/{id1}/{id2}', 'Backend\PaymentGatewayController@status')->name('admin.payment.status');
    });


    Route::get('/check/movescript', 'Backend\DashboardController@movescript')->name('admin-move-script');
    Route::get('/generate/backup', 'Backend\DashboardController@generate_bkup')->name('admin-generate-backup');
    Route::get('/activation', 'Backend\DashboardController@activation')->name('admin-activation-form');
    Route::post('/activation', 'Backend\DashboardController@activation_submit')->name('admin-activate-purchase');
    Route::get('/clear/backup', 'Backend\DashboardController@clear_bkup')->name('admin-clear-backup');


    Route::group(['middleware'=>'permissions:Manage Staff'],function(){
        Route::get('/staff/datatables', 'Backend\StaffController@datatables')->name('admin.staff.datatables');
        Route::get('/staff', 'Backend\StaffController@index')->name('admin.staff.index');
        Route::get('/staff/create', 'Backend\StaffController@create')->name('admin.staff.create');
        Route::post('/staff/store', 'Backend\StaffController@store')->name('admin.staff.store');
        Route::get('/staff/edit/{id}', 'Backend\StaffController@edit')->name('admin.staff.edit');
        Route::post('/staff/update/{id}', 'Backend\StaffController@update')->name('admin.staff.update');
        Route::get('/staff/show/{id}', 'Backend\StaffController@show')->name('admin.staff.show');
        Route::get('/staff/status/{id1}/{id2}', 'Backend\StaffController@status')->name('admin.staff.status');
        Route::get('/staff/delete/{id}', 'Backend\StaffController@destroy')->name('admin.staff.delete');
    });




    Route::group(['middleware'=>'permissions:Manage Roles'],function(){
        Route::get('/role/datatables', 'Backend\RoleController@datatables')->name('admin-role-datatables');
        Route::get('/role', 'Backend\RoleController@index')->name('admin-role-index');
        Route::get('/role/create', 'Backend\RoleController@create')->name('admin-role-create');
        Route::post('/role/create', 'Backend\RoleController@store')->name('admin-role-store');
        Route::get('/role/edit/{id}', 'Backend\RoleController@edit')->name('admin-role-edit');
        Route::post('role/edit/{id}', 'Backend\RoleController@update')->name('admin-role-update');
        Route::get('/role/delete/{id}', 'Backend\RoleController@destroy')->name('admin-role-delete');
        Route::get('/role/check', 'Backend\RoleController@roleexistencecheck')->name('admin-role-existence-check');
    });




    Route::group(['middleware'=>'permissions:role'],function(){
        Route::get('/menu/datatables', 'Backend\RoleController@menudatatables')->name('admin-menu-datatables');
        Route::post('/menu/create', 'Backend\RoleController@menustore')->name('admin-menu-store');
        Route::get('/menu/edit/{id}', 'Backend\RoleController@menuedit')->name('admin-menu-edit');
        Route::post('/menu/edit/{id}', 'Backend\RoleController@menuupdate')->name('admin-menu-update');
        Route::get('/menu/delete/{id}', 'Backend\RoleController@menudestroy')->name('admin-menu-delete');
    });



    Route::group(['middleware'=>'permissions:Activities'],function(){
        Route::match(['get','post'],'/systemactivities', 'Backend\DashboardController@systemActivities')->name('admin.activities');  // system activities
        Route::match(['get','post'],'/activities', 'Backend\ActivitiesController@getLoginActivities')->name('admin.loginactivities');  // get login activities
        Route::get('/activities/datatables', 'Backend\ActivitiesController@datatables')->name('admin.loginactivities.datatables');
        Route::get('/delete/login/activities', 'Backend\ActivitiesController@deleteLoginActivities')->name('admin.deleteactivities');  // delete by user_id login activities or id
    });


    Route::group(['middleware'=>'permissions:Customer List'],function(){
        Route::get('/users', 'Backend\DashboardController@userslist')->name('admin.users');
        Route::get('/userprofile', 'Backend\DashboardController@userprofile')->name('admin.userprofile');
        Route::get('/userdelete/{id}', 'Backend\DashboardController@userdelete')->name('admin.userdelete');
        Route::get('/users/datatables', 'Backend\UserController@datatables')->name('admin.user.datatables');
        Route::get('/users', 'Backend\UserController@index')->name('admin.user.index');
        Route::get('/user/banned/{id}', 'Backend\UserController@banned')->name('admin.user.banned');
        Route::get('/user/status', 'Backend\UserController@userstatus')->name('admin.userstatus');
        Route::get('/user/status/{id1}/{id2}', 'Backend\UserController@status')->name('admin.user.status');
        Route::get('/user/delete/{id}', 'Backend\UserController@delete')->name('admin.user.delete');
        Route::get('/user/increment/{id}', 'Backend\UserController@increment')->name('admin.user.increment');
        Route::post('/user/increment', 'Backend\UserController@incrementBalance')->name('admin.user.incrementBalance');
        Route::post('/user/decrement', 'Backend\UserController@decrementBalance')->name('admin.user.decrementBalance');

    });



    Route::group(['middleware'=>'permissions:General Settings'],function(){
        // currency routes
        Route::group(['currency'],function(){
            Route::get('/currency/datatables', 'Backend\CurrencyController@currencyDatatables')->name('admin.currency.datatables');
            Route::get('/currency', 'Backend\CurrencyController@index')->name('admin-currency-index');
            Route::get('/currency/create', 'Backend\CurrencyController@create')->name('admin.currency.create');
            Route::post('/addcurrency', 'Backend\CurrencyController@store')->name('admin-currency-store');
            Route::get('/update/currency/status', 'Backend\CurrencyController@updatestatus')->name('admin.update.currency.status');
            Route::get('/currency/edit/{id}', 'Backend\CurrencyController@edit')->name('admin.edit.currency');
            Route::get('/currency/charge/{id}', 'Backend\CurrencyController@charge')->name('admin.charge.currency');
            Route::post('/currency/chargeupdate/{id}', 'Backend\CurrencyController@chargeupdate')->name('admin.chargeupdate.currency');
            Route::post('/currency/update/{id}', 'Backend\CurrencyController@update')->name('admin-currency-update');
            Route::get('/currency/delete/{id}', 'Backend\CurrencyController@destroy')->name('admin.currency.delete');
            Route::get('/currency/status/{id1}/{id2}', 'Backend\CurrencyController@status')->name('admin.currency.status');

        });

        Route::group(['theme'],function(){
            Route::get('/theme-settings', 'Backend\SettingsController@index')->name('admin-theme-settings');
            Route::post('/theme-settings-update', 'Backend\SettingsController@update')->name('admin-theme-settings-update');
        });

        Route::group(['localization'],function(){
            Route::get('/localization', 'Backend\SettingsController@localizationFormShow')->name('admin-localization-settings');
            Route::get('/language/status', 'Backend\SettingsController@languageStatus')->name('admin-language-change-status');
            Route::get('/language/default', 'Backend\SettingsController@languageSetDefault')->name('admin-language-set-default');
            Route::get('/language/edit', 'Backend\SettingsController@languageEditForm')->name('admin-language-edit');
            Route::post('/language/update', 'Backend\SettingsController@languageUpdate')->name('admin-language-content-update');
            Route::post('/language/add', 'Backend\SettingsController@languageAdd')->name('admin-language-add');
            Route::get('/language/delete/{id}', 'Backend\SettingsController@languageDelete')->name('admin-language-delete');
        });
    });



    Route::group(['prefix'=>'transaction','middleware'=>'permissions:Manage Transaction'],function(){
        // transaction routes
        Route::get('/', 'Backend\TransactionController@allTransactions')->name('admin-all-transaction');
        Route::get('/list', 'Backend\TransactionController@transactionsDatatable')->name('admin.transaction.datatables');
        Route::get('/details/{transactionid}', 'Backend\TransactionController@transactionsdetails')->name('admin.transaction.details');
        Route::get('/complete-transaction/{transactionid}', 'Backend\TransactionController@completetransaction')->name('complete-transaction-menual');
        Route::get('/transaction/status/{id1}/{id2}', 'Backend\TransactionController@status')->name('admin.transaction.status');
        Route::get('/transaction/delete/{id}', 'Backend\TransactionController@delete')->name('admin.transaction.delete');

        Route::get('/withdraw', 'Backend\TransactionController@withdraw')->name('admin-withdraw-money');
        Route::get('/withdraw-datalist', 'Backend\TransactionController@withdrawlatalist')->name('admin.withdraw.datalist');
        Route::get('/withdraw-request', 'Backend\TransactionController@request')->name('admin-money-withdraw-request');
        Route::get('/withdraw/details/{id}', 'Backend\TransactionController@withdrawdetails')->name('admin-withdraw-details');
        Route::get('/withdraw-approve/{id}', 'Backend\TransactionController@approve')->name('admin.withdraw.approve');
        Route::get('/withdraw/delete/{id}', 'Backend\TransactionController@withdrawdelete')->name('admin.withdraw.delete');


        Route::get('/deposit', 'Backend\DepositController@index')->name('admin.deposit.money');
        Route::get('/deposit/datatables', 'Backend\DepositController@datatables')->name('admin.deposit.datatables');
        Route::get('/deposit-details/{id}', 'Backend\DepositController@details')->name('admin.deposit.details');
        Route::get('/complete-deposit/{depositid}', 'Backend\DepositController@completedeposit')->name('admin.complete.deposit.menual');
    });



    Route::group(['middleware'=>'permissions:Card Bank Accounts'],function(){
        // bank account routes routes
        Route::get('/banckaccounts', 'Backend\BankaccountsController@index')->name('admin.all.bankaccounts');
        Route::get('/banckaccounts/datalist', 'Backend\BankaccountsController@datatables')->name('admin.bankaccount.datatable');
        Route::get('bankaccounts/delete/{id}','Backend\BankaccountsController@delete')->name('admin.bankaccount.delete');
        Route::get('bankaccounts/show/{id}','Backend\BankaccountsController@bankaccountshow')->name('admin.bankaccount.show');
        Route::get('approveaccount/{id}','Backend\BankaccountsController@approveaccount')->name('admin-approve-bankaccount');
        // Route::get('bankaccounts/status/','Backend\BankaccountsController@bankaccountstatus')->name('admin-bankaccount-status-change');
        Route::get('bankaccounts/status/{id1}/{id2}','Backend\BankaccountsController@status')->name('admin.bankaccount.status');

        // Cradit card routes
        Route::get('/cards', 'Backend\CardController@index')->name('admin.cards.index');
        Route::get('/cards/datatables', 'Backend\CardController@datatables')->name('admin.cards.datatable');
        Route::get('craditcard/delete/{id}','Backend\CardController@delete')->name('admin.cradit.delete');
        Route::get('craditcard/status/{id1}/{id2}','Backend\CardController@status')->name('admin.craditcard.status');
        Route::get('craditcard/show/{id}','Backend\CardController@show')->name('admin.cradit.show');
        Route::get('approvecraditcard/{id}','Backend\CardController@approve')->name('admin-approve-craditcard');
    });


    Route::group(['middleware'=>'permissions:Support Ticket'],function(){
         Route::get('/support-ticket/datatables', 'Backend\SupportController@datatables')->name('admin.support.datatable');
         Route::get('support-ticket','Backend\SupportController@index')->name('admin.support.ticket');
         Route::get('support-ticket-delete/{id}','Backend\SupportController@delete')->name('admin.support.ticket.delete');
         Route::get('support-ticket-details/{id}','Backend\SupportController@view')->name('admin.support.ticket.view');
         Route::get('support-ticket-status','Backend\SupportController@changestatus')->name('admin-support-ticket-status');

         Route::post('support-ticket/message/post','Backend\SupportController@message')->name('admin.support.ticket.message');
    });

    Route::group(['middleware'=>'permissions:Subscribers'],function(){
         Route::get('/subscriber/datatables','Backend\SubscriberController@datatables')->name('admin.subscriber.datatables');
         Route::get('subscribers','Backend\SubscriberController@index')->name('admin.subscribers.list');
         Route::get('/subscribers/download', 'Backend\SubscriberController@download')->name('admin-subs-download');

         Route::get('subscribers/delete','Backend\SubscriberController@delete')->name('admin-subscriber-delete');
    });




});

