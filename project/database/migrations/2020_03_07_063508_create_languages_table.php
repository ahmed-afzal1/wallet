<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('iso_639');
            $table->string('status')->default(1);
            $table->timestamps();
        });

        $data =array(
            array('id' => '1','name' => 'English','iso_639' => 'en','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','name' => 'Afar','iso_639' => 'aa','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '3','name' => 'Abkhazian','iso_639' => 'ab','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','name' => 'Afrikaans','iso_639' => 'af','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','name' => 'Amharic','iso_639' => 'am','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '6','name' => 'Arabic','iso_639' => 'ar','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '7','name' => 'Assamese','iso_639' => 'as','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '8','name' => 'Aymara','iso_639' => 'ay','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '9','name' => 'Azerbaijani','iso_639' => 'az','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '10','name' => 'Bashkir','iso_639' => 'ba','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '11','name' => 'Belarusian','iso_639' => 'be','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '12','name' => 'Bulgarian','iso_639' => 'bg','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '13','name' => 'Bihari','iso_639' => 'bh','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '14','name' => 'Bislama','iso_639' => 'bi','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '15','name' => 'Bengali/Bangla','iso_639' => 'bn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '16','name' => 'Tibetan','iso_639' => 'bo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '17','name' => 'Breton','iso_639' => 'br','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '18','name' => 'Catalan','iso_639' => 'ca','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '19','name' => 'Corsican','iso_639' => 'co','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '20','name' => 'Czech','iso_639' => 'cs','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '21','name' => 'Welsh','iso_639' => 'cy','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '22','name' => 'Danish','iso_639' => 'da','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '23','name' => 'German','iso_639' => 'de','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '24','name' => 'Bhutani','iso_639' => 'dz','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '25','name' => 'Greek','iso_639' => 'el','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '26','name' => 'Esperanto','iso_639' => 'eo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '27','name' => 'Spanish','iso_639' => 'es','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '28','name' => 'Estonian','iso_639' => 'et','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '29','name' => 'Basque','iso_639' => 'eu','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '30','name' => 'Persian','iso_639' => 'fa','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '31','name' => 'Finnish','iso_639' => 'fi','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '32','name' => 'Fiji','iso_639' => 'fj','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '33','name' => 'Faeroese','iso_639' => 'fo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '34','name' => 'French','iso_639' => 'fr','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '35','name' => 'Frisian','iso_639' => 'fy','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '36','name' => 'Irish','iso_639' => 'ga','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '37','name' => 'Scots/Gaelic','iso_639' => 'gd','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '38','name' => 'Galician','iso_639' => 'gl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '39','name' => 'Guarani','iso_639' => 'gn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '40','name' => 'Gujarati','iso_639' => 'gu','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '41','name' => 'Hausa','iso_639' => 'ha','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '42','name' => 'Hindi','iso_639' => 'hi','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '43','name' => 'Croatian','iso_639' => 'hr','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '44','name' => 'Hungarian','iso_639' => 'hu','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '45','name' => 'Armenian','iso_639' => 'hy','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '46','name' => 'Interlingua','iso_639' => 'ia','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '47','name' => 'Interlingue','iso_639' => 'ie','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '48','name' => 'Inupiak','iso_639' => 'ik','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '49','name' => 'Indonesian','iso_639' => 'in','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '50','name' => 'Icelandic','iso_639' => 'is','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '51','name' => 'Italian','iso_639' => 'it','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '52','name' => 'Hebrew','iso_639' => 'iw','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '53','name' => 'Japanese','iso_639' => 'ja','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '54','name' => 'Yiddish','iso_639' => 'ji','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '55','name' => 'Javanese','iso_639' => 'jw','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '56','name' => 'Georgian','iso_639' => 'ka','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '57','name' => 'Kazakh','iso_639' => 'kk','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '58','name' => 'Greenlandic','iso_639' => 'kl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '59','name' => 'Cambodian','iso_639' => 'km','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '60','name' => 'Kannada','iso_639' => 'kn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '61','name' => 'Korean','iso_639' => 'ko','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '62','name' => 'Kashmiri','iso_639' => 'ks','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '63','name' => 'Kurdish','iso_639' => 'ku','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '64','name' => 'Kirghiz','iso_639' => 'ky','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '65','name' => 'Latin','iso_639' => 'la','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '66','name' => 'Lingala','iso_639' => 'ln','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '67','name' => 'Laothian','iso_639' => 'lo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '68','name' => 'Lithuanian','iso_639' => 'lt','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '69','name' => 'Latvian/Lettish','iso_639' => 'lv','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '70','name' => 'Malagasy','iso_639' => 'mg','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '71','name' => 'Maori','iso_639' => 'mi','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '72','name' => 'Macedonian','iso_639' => 'mk','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '73','name' => 'Malayalam','iso_639' => 'ml','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '74','name' => 'Mongolian','iso_639' => 'mn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '75','name' => 'Moldavian','iso_639' => 'mo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '76','name' => 'Marathi','iso_639' => 'mr','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '77','name' => 'Malay','iso_639' => 'ms','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '78','name' => 'Maltese','iso_639' => 'mt','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '79','name' => 'Burmese','iso_639' => 'my','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '80','name' => 'Nauru','iso_639' => 'na','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '81','name' => 'Nepali','iso_639' => 'ne','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '82','name' => 'Dutch','iso_639' => 'nl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '83','name' => 'Norwegian','iso_639' => 'no','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '84','name' => 'Occitan','iso_639' => 'oc','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '85','name' => '(Afan)/Oromoor/Oriya','iso_639' => 'om','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '86','name' => 'Punjabi','iso_639' => 'pa','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '87','name' => 'Polish','iso_639' => 'pl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '88','name' => 'Pashto/Pushto','iso_639' => 'ps','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '89','name' => 'Portuguese','iso_639' => 'pt','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '90','name' => 'Quechua','iso_639' => 'qu','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '91','name' => 'Rhaeto-Romance','iso_639' => 'rm','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '92','name' => 'Kirundi','iso_639' => 'rn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '93','name' => 'Romanian','iso_639' => 'ro','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '94','name' => 'Russian','iso_639' => 'ru','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '95','name' => 'Kinyarwanda','iso_639' => 'rw','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '96','name' => 'Sanskrit','iso_639' => 'sa','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '97','name' => 'Sindhi','iso_639' => 'sd','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '98','name' => 'Sangro','iso_639' => 'sg','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '99','name' => 'Serbo-Croatian','iso_639' => 'sh','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '100','name' => 'Singhalese','iso_639' => 'si','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '101','name' => 'Slovak','iso_639' => 'sk','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '102','name' => 'Slovenian','iso_639' => 'sl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '103','name' => 'Samoan','iso_639' => 'sm','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '104','name' => 'Shona','iso_639' => 'sn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '105','name' => 'Somali','iso_639' => 'so','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '106','name' => 'Albanian','iso_639' => 'sq','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '107','name' => 'Serbian','iso_639' => 'sr','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '108','name' => 'Siswati','iso_639' => 'ss','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '109','name' => 'Sesotho','iso_639' => 'st','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '110','name' => 'Sundanese','iso_639' => 'su','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '111','name' => 'Swedish','iso_639' => 'sv','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '112','name' => 'Swahili','iso_639' => 'sw','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '113','name' => 'Tamil','iso_639' => 'ta','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '114','name' => 'Telugu','iso_639' => 'te','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '115','name' => 'Tajik','iso_639' => 'tg','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '116','name' => 'Thai','iso_639' => 'th','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '117','name' => 'Tigrinya','iso_639' => 'ti','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '118','name' => 'Turkmen','iso_639' => 'tk','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '119','name' => 'Tagalog','iso_639' => 'tl','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '120','name' => 'Setswana','iso_639' => 'tn','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '121','name' => 'Tonga','iso_639' => 'to','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '122','name' => 'Turkish','iso_639' => 'tr','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '123','name' => 'Tsonga','iso_639' => 'ts','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '124','name' => 'Tatar','iso_639' => 'tt','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '125','name' => 'Twi','iso_639' => 'tw','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '126','name' => 'Ukrainian','iso_639' => 'uk','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '127','name' => 'Urdu','iso_639' => 'ur','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '128','name' => 'Uzbek','iso_639' => 'uz','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '129','name' => 'Vietnamese','iso_639' => 'vi','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '130','name' => 'Volapuk','iso_639' => 'vo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '131','name' => 'Wolof','iso_639' => 'wo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '132','name' => 'Xhosa','iso_639' => 'xh','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '133','name' => 'Yoruba','iso_639' => 'yo','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '134','name' => 'Chinese','iso_639' => 'zh','status' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '135','name' => 'Zulu','iso_639' => 'zu','status' => '1','created_at' => NULL,'updated_at' => NULL)
          );

        DB::table('languages')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
