<?php
namespace App\Http\Traits;

use App\Models\Localization as AppLocalization;
use Illuminate\Support\Facades\App;

trait Localization{
    public $defaultAdminLanguage;

        public function __construct()
        {
            $this->middleware('auth:admin');
            $this->defaultAdminLanguage = AppLocalization::where('is_default',1)->first();
            App::setLocale($this->defaultAdminLanguage->language_code);
        }
}