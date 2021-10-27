<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moneyrequests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_id');
            $table->string('request_to');
            $table->string('request_from');
            $table->double('amount');
            $table->double('transaction_cost');
            $table->string('currency_id');
            $table->longText('referance')->nullable();
            $table->string('costpaid_by')->nullable();
            $table->enum('status',['pending','completed','canceled'])->default('pending');
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
        Schema::dropIfExists('moneyrequests');
    }
}
