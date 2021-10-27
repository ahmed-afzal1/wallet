<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('country_id')->unique();
            $table->string('code')->unique();
            $table->string('sign')->unique();
            $table->enum('sign_position',['left','right'])->default('left');
            $table->double('rate')->default(0);
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
                
        DB::table('currencies')->insert(array(
            'name' => 'Dollar',
            'country_id' => '233',
            'code' => 'USD',
            'sign' => '$',
            'rate' => '85',
            'is_default' => '1',
            'created_at' => date('Y-m-d'),
            'status' => 1 
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
