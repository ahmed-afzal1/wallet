<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinPaymentController extends Controller
{
    public function __construct() {
        // $this->middleware('auth')->except(['coingetCallback']);
     }
 
     public function blockInvest()
     {
         return view('frontend.coinpay');
     }
 
}
