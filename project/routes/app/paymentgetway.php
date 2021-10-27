<?php

Route::get('stripe', 'Paymentgateway\StripePaymentController@stripe');
Route::post('stripe', 'Paymentgateway\StripePaymentController@stripePost')->name('stripe.post');