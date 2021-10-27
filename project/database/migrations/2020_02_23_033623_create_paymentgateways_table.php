<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentgatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentgateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('text')->nullable();
            $table->text('token')->nullable();
            $table->text('sendbox')->nullable();
            $table->text('email')->nullable();
            $table->text('business')->nullable();
            $table->text('merchant')->nullable();
            $table->text('website')->nullable();
            $table->text('mode')->nullable();
            $table->text('currency')->nullable();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentgateways');
    }
}
