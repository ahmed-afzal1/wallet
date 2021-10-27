<?php


Route::redirect('admin', 'admin/login');

Route::prefix('admin')->group(function(){


        Route::get('/login','Admin\LoginController@showLoginForm')->name('admin.login');
        Route::post('/login','Admin\LoginController@login')->name('admin.login.submit');

        // Forgot Routes
        Route::get('/forgot', 'Admin\LoginController@showForgotForm')->name('admin.forgot');
        Route::post('/forgot', 'Admin\LoginController@forgot')->name('admin.forgot.submit');

        Route::get('/balanceconvert', 'ToolsController@balanceconvert')->name('balanceconvert');
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');


        Route::group(['middleware'=>'permissions:Manage Blog'],function(){
            //------------ ADMIN BLOG SECTION ------------
              Route::get('/blog/datatables', 'Admin\BlogController@datatables')->name('admin-blog-datatables'); //JSON REQUEST
              Route::get('/blog', 'Admin\BlogController@index')->name('admin-blog-index');
              Route::get('/blog/create', 'Admin\BlogController@create')->name('admin-blog-create');
              Route::post('/blog/create', 'Admin\BlogController@store')->name('admin-blog-store');
              Route::get('/blog/edit/{id}', 'Admin\BlogController@edit')->name('admin-blog-edit');
              Route::post('/blog/edit/{id}', 'Admin\BlogController@update')->name('admin-blog-update');
              Route::get('/blog/delete/{id}', 'Admin\BlogController@destroy')->name('admin-blog-delete');
      
              Route::get('/blog/category/datatables', 'Admin\BlogCategoryController@datatables')->name('admin-cblog-datatables'); //JSON REQUEST
              Route::get('/blog/category', 'Admin\BlogCategoryController@index')->name('admin-cblog-index');
              Route::get('/blog/category/create', 'Admin\BlogCategoryController@create')->name('admin-cblog-create');
              Route::post('/blog/category/create', 'Admin\BlogCategoryController@store')->name('admin-cblog-store');
              Route::get('/blog/category/edit/{id}', 'Admin\BlogCategoryController@edit')->name('admin-cblog-edit');
              Route::post('/blog/category/edit/{id}', 'Admin\BlogCategoryController@update')->name('admin-cblog-update');
              Route::get('/blog/category/delete/{id}', 'Admin\BlogCategoryController@destroy')->name('admin-cblog-delete');
            //------------ ADMIN BLOG SECTION ENDS ------------
        });

        Route::group(['middleware'=>'permissions:Home Page Settings'],function(){

            //-------------SLIDER SECTION -------------------
            Route::get('/slider/datatables', 'Admin\SliderController@datatables')->name('admin.slider.datatables');
            Route::get('/slider', 'Admin\SliderController@index')->name('admin.slider');           // Admin Dashboard route
            Route::get('/slider/create', 'Admin\SliderController@create')->name('admin.slider.create');
            Route::post('/slider/store', 'Admin\SliderController@store')->name('admin-sl-store');
            Route::get('/slider/edit/{id}', 'Admin\SliderController@edit')->name('admin-sl-edit');
            Route::post('/slider/edit/{id}', 'Admin\SliderController@update')->name('admin-sl-update');
            Route::get('/slider/delete/{id}', 'Admin\SliderController@destroy')->name('admin-sl-delete');
            //-------------SLIDER SECTION ENDS-------------------


            //-------------SERVICE SECTION -------------------
            Route::get('/service/datatables', 'Admin\ServiceController@datatables')->name('admin-service-datatables');
            Route::get('/service', 'Admin\ServiceController@index')->name('admin-service-index');
            Route::get('/service/create', 'Admin\ServiceController@create')->name('admin-service-create');
            Route::post('/service/create', 'Admin\ServiceController@store')->name('admin-service-store');
            Route::get('/service/edit/{id}', 'Admin\ServiceController@edit')->name('admin-service-edit');
            Route::post('/service/edit/{id}', 'Admin\ServiceController@update')->name('admin-service-update');
            Route::get('/service/delete/{id}', 'Admin\ServiceController@destroy')->name('admin-service-delete');
            //-------------SERVICE SECTION ENDS-------------------

            //-------------PROJECT SECTION -------------------
            Route::get('/project/datatables', 'Admin\PortfolioController@datatables')->name('admin-portfolio-datatables');
            Route::get('/projects', 'Admin\PortfolioController@index')->name('admin-portfolio-index');
            Route::get('/projects/create', 'Admin\PortfolioController@create')->name('admin-portfolio-create');
            Route::post('/projects/create', 'Admin\PortfolioController@store')->name('admin-portfolio-store');
            Route::get('/projects/edit/{id}', 'Admin\PortfolioController@edit')->name('admin-portfolio-edit');
            Route::post('/projects/edit/{id}', 'Admin\PortfolioController@update')->name('admin-portfolio-update');
            Route::get('/projects/delete/{id}', 'Admin\PortfolioController@destroy')->name('admin-portfolio-delete');
            //-------------PROJECT SECTION ENDS-------------------


            //-------------REVIEW SECTION -------------------
            Route::get('/review/datatables', 'Admin\ReviewController@datatables')->name('admin-review-datatables'); //JSON REQUEST
            Route::get('/review', 'Admin\ReviewController@index')->name('admin-review-index');
            Route::get('/review/create', 'Admin\ReviewController@create')->name('admin-review-create');
            Route::post('/review/create', 'Admin\ReviewController@store')->name('admin-review-store');
            Route::get('/review/edit/{id}', 'Admin\ReviewController@edit')->name('admin-review-edit');
            Route::post('/review/edit/{id}', 'Admin\ReviewController@update')->name('admin-review-update');
            Route::get('/review/delete/{id}', 'Admin\ReviewController@destroy')->name('admin-review-delete');
            //-------------REVIEW SECTION ENDS-------------------

            Route::get('/page-settings/customize', 'Admin\PageSettingController@customize')->name('admin-ps-customize');
            Route::get('/page-settings/homecontact', 'Admin\PageSettingController@homecontact')->name('admin-ps-homecontact');

        });

        Route::group(['middleware'=>'permissions:Menu Page Settings'],function(){
            //-------------FAQ SECTION -------------------
            Route::get('/faq/datatables', 'Admin\FaqController@datatables')->name('admin-faq-datatables');
            Route::get('/admin-faq', 'Admin\FaqController@index')->name('admin-faq-index');
            Route::get('/faq/create', 'Admin\FaqController@create')->name('admin-faq-create');
            Route::get('/faq/edit/{id}', 'Admin\FaqController@edit')->name('admin-faq-edit');
            Route::get('/faq/delete/{id}', 'Admin\FaqController@destroy')->name('admin-faq-delete');
            Route::post('/faq/update/{id}', 'Admin\FaqController@update')->name('admin-faq-update');
            Route::post('/faq/create', 'Admin\FaqController@store')->name('admin-faq-store');
            //-------------FAQ SECTION ENDS-------------------

            //-------------PAGE SECTION -------------------
            Route::get('/page/datatables', 'Admin\PageController@datatables')->name('admin-page-datatables');
            Route::get('/page', 'Admin\PageController@index')->name('admin-page-index');
            Route::get('/page/create', 'Admin\PageController@create')->name('admin-page-create');
            Route::get('/page/edit/{id}', 'Admin\PageController@edit')->name('admin-page-edit');
            Route::post('/page/update/{id}', 'Admin\PageController@update')->name('admin-page-update');
            Route::get('/page/delete/{id}', 'Admin\PageController@destroy')->name('admin-page-delete');
            Route::get('/page/status/{id1}/{id2}', 'Admin\PageController@status')->name('admin-page-status');
            Route::post('/page/create', 'Admin\PageController@store')->name('admin-page-store');
            //-------------PAGE SECTION ENDS-------------------


            Route::get('/page-settings/contact', 'Admin\PageSettingController@contact')->name('admin-ps-contact');
            Route::get('/page-settings/experience', 'Admin\PageSettingController@video')->name('admin-ps-video');
            Route::get('/page-settings/homecontact', 'Admin\PageSettingController@homecontact')->name('admin-ps-homecontact');
            Route::get('/page-settings/present', 'Admin\PageSettingController@present')->name('admin-ps-present');
            Route::get('/page-settings/blog', 'Admin\PageSettingController@blog')->name('admin-ps-blog');
            Route::post('/page-settings/update/all', 'Admin\PageSettingController@update')->name('admin-ps-update');
            Route::post('/page-settings/update/home', 'Admin\PageSettingController@homeupdate')->name('admin-ps-homeupdate');
            Route::get('/general-settings/status/{field}/{status}', 'Admin\SettingsController@status')->name('admin-gs-status');

        });



    Route::group(['profile'],function(){
        Route::get('/logout', 'Admin\AdminController@logout')->name('admin.logout');                      // Logout Route
        Route::get('/myprofile', 'Admin\DashboardController@profile')->name('admin.profile');           // Profile related routes
        Route::get('/updatestatus/{id1}/{id2}', 'Admin\StaffController@updatestatus')->name('admin.updatestatus');    // admin status update
        Route::get('/permitless', 'Admin\DashboardController@permitless')->name('admin.permitless');    // admin status update
        Route::post('/changepassword/{id}', 'Admin\DashboardController@changepassword')->name('admin-changepassword');    // admin password update
    });


    Route::group(['middleware' => 'permissions:Payment Settings'], function () {
        Route::get('/paymentgateway/datatables', 'Admin\PaymentGatewayController@datatables')->name('admin.payment.datatables'); //JSON REQUEST
        Route::get('/paymentgateway', 'Admin\PaymentGatewayController@index')->name('admin.payment.index');
        Route::get('/paymentgateway/create', 'Admin\PaymentGatewayController@create')->name('admin.payment.create');
        Route::post('/paymentgateway/create', 'Admin\PaymentGatewayController@store')->name('admin.payment.store');
        Route::get('/paymentgateway/edit/{id}', 'Admin\PaymentGatewayController@edit')->name('admin.payment.edit');
        Route::post('/paymentgateway/update/{id}', 'Admin\PaymentGatewayController@update')->name('admin.payment.update');
        Route::get('/paymentgateway/delete/{id}', 'Admin\PaymentGatewayController@destroy')->name('admin.payment.delete');
        Route::get('/paymentgateway/status/{id1}/{id2}', 'Admin\PaymentGatewayController@status')->name('admin.payment.status');
    });


    Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
    Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
    Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
    Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
    Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');


    Route::group(['middleware'=>'permissions:Manage Staff'],function(){
        Route::get('/staff/datatables', 'Admin\StaffController@datatables')->name('admin.staff.datatables');
        Route::get('/staff', 'Admin\StaffController@index')->name('admin.staff.index');
        Route::get('/staff/create', 'Admin\StaffController@create')->name('admin.staff.create');
        Route::post('/staff/store', 'Admin\StaffController@store')->name('admin.staff.store');
        Route::get('/staff/edit/{id}', 'Admin\StaffController@edit')->name('admin.staff.edit');
        Route::post('/staff/update/{id}', 'Admin\StaffController@update')->name('admin.staff.update');
        Route::get('/staff/show/{id}', 'Admin\StaffController@show')->name('admin.staff.show');
        Route::get('/staff/status/{id1}/{id2}', 'Admin\StaffController@status')->name('admin.staff.status');
        Route::get('/staff/delete/{id}', 'Admin\StaffController@destroy')->name('admin.staff.delete');
    });




    Route::group(['middleware'=>'permissions:Manage Roles'],function(){
        Route::get('/role/datatables', 'Admin\RoleController@datatables')->name('admin-role-datatables');
        Route::get('/role', 'Admin\RoleController@index')->name('admin-role-index');
        Route::get('/role/create', 'Admin\RoleController@create')->name('admin-role-create');
        Route::post('/role/create', 'Admin\RoleController@store')->name('admin-role-store');
        Route::get('/role/edit/{id}', 'Admin\RoleController@edit')->name('admin-role-edit');
        Route::post('role/edit/{id}', 'Admin\RoleController@update')->name('admin-role-update');
        Route::get('/role/delete/{id}', 'Admin\RoleController@destroy')->name('admin-role-delete');
        Route::get('/role/check', 'Admin\RoleController@roleexistencecheck')->name('admin-role-existence-check');
    });




    Route::group(['middleware'=>'permissions:role'],function(){
        Route::get('/menu/datatables', 'Admin\RoleController@menudatatables')->name('admin-menu-datatables');
        Route::post('/menu/create', 'Admin\RoleController@menustore')->name('admin-menu-store');
        Route::get('/menu/edit/{id}', 'Admin\RoleController@menuedit')->name('admin-menu-edit');
        Route::post('/menu/edit/{id}', 'Admin\RoleController@menuupdate')->name('admin-menu-update');
        Route::get('/menu/delete/{id}', 'Admin\RoleController@menudestroy')->name('admin-menu-delete');
    });



    Route::group(['middleware'=>'permissions:Activities'],function(){
        Route::match(['get','post'],'/systemactivities', 'Admin\DashboardController@systemActivities')->name('admin.activities');  // system activities
        Route::match(['get','post'],'/activities', 'Admin\ActivitiesController@getLoginActivities')->name('admin.loginactivities');  // get login activities
        Route::get('/activities/datatables', 'Admin\ActivitiesController@datatables')->name('admin.loginactivities.datatables');
        Route::get('/delete/login/activities', 'Admin\ActivitiesController@deleteLoginActivities')->name('admin.deleteactivities');  // delete by user_id login activities or id
    });


    Route::group(['middleware'=>'permissions:Customer List'],function(){
        Route::get('/users', 'Admin\DashboardController@userslist')->name('admin.users');
        Route::get('/userprofile', 'Admin\DashboardController@userprofile')->name('admin.userprofile');
        Route::get('/userdelete/{id}', 'Admin\DashboardController@userdelete')->name('admin.userdelete');
        Route::get('/users/datatables', 'Admin\UserController@datatables')->name('admin.user.datatables');
        Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
        Route::get('/user/banned/{id}', 'Admin\UserController@banned')->name('admin.user.banned');
        Route::get('/user/status', 'Admin\UserController@userstatus')->name('admin.userstatus');
        Route::get('/user/status/{id1}/{id2}', 'Admin\UserController@status')->name('admin.user.status');
        Route::get('/user/delete/{id}', 'Admin\UserController@delete')->name('admin.user.delete');
        Route::get('/user/increment/{id}', 'Admin\UserController@increment')->name('admin.user.increment');
        Route::post('/user/increment', 'Admin\UserController@incrementBalance')->name('admin.user.incrementBalance');
        Route::post('/user/decrement', 'Admin\UserController@decrementBalance')->name('admin.user.decrementBalance');

    });



    Route::group(['middleware'=>'permissions:General Settings'],function(){
        // currency routes
        Route::group(['currency'],function(){
            Route::get('/currency/datatables', 'Admin\CurrencyController@currencyDatatables')->name('admin.currency.datatables');
            Route::get('/currency', 'Admin\CurrencyController@index')->name('admin-currency-index');
            Route::get('/currency/create', 'Admin\CurrencyController@create')->name('admin.currency.create');
            Route::post('/addcurrency', 'Admin\CurrencyController@store')->name('admin-currency-store');
            Route::get('/update/currency/status', 'Admin\CurrencyController@updatestatus')->name('admin.update.currency.status');
            Route::get('/currency/edit/{id}', 'Admin\CurrencyController@edit')->name('admin.edit.currency');
            Route::get('/currency/charge/{id}', 'Admin\CurrencyController@charge')->name('admin.charge.currency');
            Route::post('/currency/chargeupdate/{id}', 'Admin\CurrencyController@chargeupdate')->name('admin.chargeupdate.currency');
            Route::post('/currency/update/{id}', 'Admin\CurrencyController@update')->name('admin-currency-update');
            Route::get('/currency/delete/{id}', 'Admin\CurrencyController@destroy')->name('admin.currency.delete');
            Route::get('/currency/status/{id1}/{id2}', 'Admin\CurrencyController@status')->name('admin.currency.status');

        });

        Route::group(['theme'],function(){
            Route::get('/theme-settings', 'Admin\SettingsController@index')->name('admin-theme-settings');
            Route::post('/theme-settings-update', 'Admin\SettingsController@update')->name('admin-theme-settings-update');
        });

        Route::group(['localization'],function(){
            Route::get('/localization', 'Admin\SettingsController@localizationFormShow')->name('admin-localization-settings');
            Route::get('/language/status', 'Admin\SettingsController@languageStatus')->name('admin-language-change-status');
            Route::get('/language/default', 'Admin\SettingsController@languageSetDefault')->name('admin-language-set-default');
            Route::get('/language/edit', 'Admin\SettingsController@languageEditForm')->name('admin-language-edit');
            Route::post('/language/update', 'Admin\SettingsController@languageUpdate')->name('admin-language-content-update');
            Route::post('/language/add', 'Admin\SettingsController@languageAdd')->name('admin-language-add');
            Route::get('/language/delete/{id}', 'Admin\SettingsController@languageDelete')->name('admin-language-delete');
        });
    });



    Route::group(['prefix'=>'transaction','middleware'=>'permissions:Manage Transaction'],function(){
        // transaction routes
        Route::get('/', 'Admin\TransactionController@allTransactions')->name('admin-all-transaction');
        Route::get('/list', 'Admin\TransactionController@transactionsDatatable')->name('admin.transaction.datatables');
        Route::get('/details/{transactionid}', 'Admin\TransactionController@transactionsdetails')->name('admin.transaction.details');
        Route::get('/complete-transaction/{transactionid}', 'Admin\TransactionController@completetransaction')->name('complete-transaction-menual');
        Route::get('/transaction/status/{id1}/{id2}', 'Admin\TransactionController@status')->name('admin.transaction.status');
        Route::get('/transaction/delete/{id}', 'Admin\TransactionController@delete')->name('admin.transaction.delete');

        Route::get('/withdraw', 'Admin\TransactionController@withdraw')->name('admin-withdraw-money');
        Route::get('/withdraw-datalist', 'Admin\TransactionController@withdrawlatalist')->name('admin.withdraw.datalist');
        Route::get('/withdraw-request', 'Admin\TransactionController@request')->name('admin-money-withdraw-request');
        Route::get('/withdraw/details/{id}', 'Admin\TransactionController@withdrawdetails')->name('admin-withdraw-details');
        Route::get('/withdraw-approve/{id}', 'Admin\TransactionController@approve')->name('admin.withdraw.approve');
        Route::get('/withdraw/delete/{id}', 'Admin\TransactionController@withdrawdelete')->name('admin.withdraw.delete');


        Route::get('/deposit', 'Admin\DepositController@index')->name('admin.deposit.money');
        Route::get('/deposit/datatables', 'Admin\DepositController@datatables')->name('admin.deposit.datatables');
        Route::get('/deposit-details/{id}', 'Admin\DepositController@details')->name('admin.deposit.details');
        Route::get('/complete-deposit/{depositid}', 'Admin\DepositController@completedeposit')->name('admin.complete.deposit.menual');
    });



    Route::group(['middleware'=>'permissions:Card Bank Accounts'],function(){
        // bank account routes routes
        Route::get('/banckaccounts', 'Admin\BankaccountsController@index')->name('admin.all.bankaccounts');
        Route::get('/banckaccounts/datalist', 'Admin\BankaccountsController@datatables')->name('admin.bankaccount.datatable');
        Route::get('bankaccounts/delete/{id}','Admin\BankaccountsController@delete')->name('admin.bankaccount.delete');
        Route::get('bankaccounts/show/{id}','Admin\BankaccountsController@bankaccountshow')->name('admin.bankaccount.show');
        Route::get('approveaccount/{id}','Admin\BankaccountsController@approveaccount')->name('admin-approve-bankaccount');
        // Route::get('bankaccounts/status/','Admin\BankaccountsController@bankaccountstatus')->name('admin-bankaccount-status-change');
        Route::get('bankaccounts/status/{id1}/{id2}','Admin\BankaccountsController@status')->name('admin.bankaccount.status');

        // Cradit card routes
        Route::get('/cards', 'Admin\CardController@index')->name('admin.cards.index');
        Route::get('/cards/datatables', 'Admin\CardController@datatables')->name('admin.cards.datatable');
        Route::get('craditcard/delete/{id}','Admin\CardController@delete')->name('admin.cradit.delete');
        Route::get('craditcard/status/{id1}/{id2}','Admin\CardController@status')->name('admin.craditcard.status');
        Route::get('craditcard/show/{id}','Admin\CardController@show')->name('admin.cradit.show');
        Route::get('approvecraditcard/{id}','Admin\CardController@approve')->name('admin-approve-craditcard');
    });


    Route::group(['middleware'=>'permissions:Support Ticket'],function(){
         Route::get('/support-ticket/datatables', 'Admin\SupportController@datatables')->name('admin.support.datatable');
         Route::get('support-ticket','Admin\SupportController@index')->name('admin.support.ticket');
         Route::get('support-ticket-delete/{id}','Admin\SupportController@delete')->name('admin.support.ticket.delete');
         Route::get('/support-ticket/load/{id}', 'Admin\SupportController@messageshow')->name('admin-message-load');
         Route::get('support-ticket-details/{id}','Admin\SupportController@view')->name('admin.support.ticket.view');
         Route::get('support-ticket-status','Admin\SupportController@changestatus')->name('admin-support-ticket-status');

         Route::post('support-ticket/message/post','Admin\SupportController@message')->name('admin.support.ticket.message');
    });

    Route::group(['middleware'=>'permissions:Subscribers'],function(){
         Route::get('/subscriber/datatables','Admin\SubscriberController@datatables')->name('admin.subscriber.datatables');
         Route::get('subscribers','Admin\SubscriberController@index')->name('admin.subscribers.list');
         Route::get('/subscribers/download', 'Admin\SubscriberController@download')->name('admin-subs-download');

         Route::get('subscribers/delete','Admin\SubscriberController@delete')->name('admin-subscriber-delete');
    });



});

Route::get('/invest/bitcoin', 'Userpanel\BlockChainController@blockInvest')->name('blockchain.invest');


Route::post('the/genius/ocean/2441139', 'Frontend\HomeController@subscription');
Route::get('finalize', 'Frontend\HomeController@finalize');

Route::get('/under-maintenance', 'Frontend\HomeController@maintenance')->name('front-maintenance');

    Route::get('/', 'Frontend\HomeController@index')->name('home');
    Route::get('/invalid/{type}', 'ToolsController@invalid')->name('invalid');
    Route::get('/filetree', function(){
        return view('filetree');
    });



Route::get('/api', 'Userpanel\ApiController@TransactionVerify')->name('api.check');


Route::any('/faq','Frontend\HomeController@faq')->name('front.faq');


Route::get('/service/{slug}','Frontend\HomeController@service')->name('front.service');
Route::get('/', 'Frontend\HomeController@index')->name('home');

Route::get('/project/{id}','Frontend\HomeController@project')->name('front.project');
Route::get('/about-us', 'Frontend\HomeController@about')->name('about');
Route::get('/contact','Frontend\HomeController@contact')->name('front.contact');
Route::post('/contact','Frontend\HomeController@contactemail')->name('front.contact.submit');
Route::get('/contact/refresh_code','Frontend\HomeController@refresh_code')->name('front.contact.refresh_code');

Route::get('/website/language','Frontend\HomeController@language')->name('front.lang');

Route::get('/blog','Frontend\HomeController@blog')->name('front.blog');
Route::get('/blog/{id}','Frontend\HomeController@blogshow')->name('front.blogshow');
Route::get('/blog/category/{slug}','Frontend\HomeController@blogcategory')->name('front.blogcategory');
Route::get('/blog/tag/{slug}','Frontend\HomeController@blogtags')->name('front.blogtags');
Route::get('/blog-search','Frontend\HomeController@blogsearch')->name('front.blogsearch');
Route::get('/blog/archive/{slug}','Frontend\HomeController@blogarchive')->name('front.blogarchive');
Route::get('/{slug}','Frontend\HomeController@page')->name('front.page');


   Route::group(['subscribers'],function(){
        Route::post('subscribers','Userpanel\SubscriberController@create')->name('user-subscriber-create');
    });
