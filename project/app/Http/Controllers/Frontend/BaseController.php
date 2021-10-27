<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Markury\MarkuryPost;

class BaseController extends Controller
{
    public $defaultLanguage;
    
    public function __construct()
    {
        $this->auth_guests();
        $this->defaultLanguage = Localization::where('is_default',1)->first();
        App::setLocale($this->defaultLanguage->language_code);
    }


    function auth_guests(){

        $chk = MarkuryPost::marcuryBase();
        $chkData = MarkuryPost::marcurryBase();
        if(!$chkData){
            header("Location: " . url('/install'));
            die();
        };
        if ($chk != MarkuryPost::maarcuryBase()) {
            if ($chkData < MarkuryPost::marrcuryBase()) {
                if (is_dir(base_path() . '/install')) {
                    header("Location: " . url('/install'));
                    die();
                } else {
                    echo MarkuryPost::marcuryBasee();
                    die();
                }
            }
        }
    }
}
