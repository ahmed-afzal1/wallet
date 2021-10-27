<?php
namespace App\Http\Traits;

use App\Models\Localization as AppLocalization;
use Illuminate\Support\Facades\App;

trait UserLocalization{
    public $defaultUserLanguage;

        public function __construct()
        {
            $this->middleware('auth');
            $this->defaultUserLanguage = AppLocalization::where('is_default',1)->first();
            App::setLocale($this->defaultUserLanguage->language_code);
        }
}