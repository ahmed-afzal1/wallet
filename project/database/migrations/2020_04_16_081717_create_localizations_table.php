<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateLocalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('language');
            $table->string('file');
            $table->tinyInteger('is_default')->default(0);
            $table->string('status')->default(1);
            $table->timestamps();
        });

        $data = array(
            ['id' => '1',
            'language' => 'en',
            'file' => 'en.json',
            'is_default' => '1',
            'status' => '1',
            'created_at' => Carbon::now()],
            ['id' => '2',
            'language' => 'bn',
            'file' => 'bn.json',
            'is_default' => '1',
            'status' => '1',
            'created_at' => Carbon::now()]
        );

        DB::table('localizations')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localizations');
    }
}
