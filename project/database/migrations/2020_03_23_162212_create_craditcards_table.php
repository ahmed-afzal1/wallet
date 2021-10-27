<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCraditcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('craditcards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_email');
            $table->string('bank_name')->nullable();
            $table->string('card_owner_name');
            $table->string('card_number');
            $table->string('card_type')->nullable();
            $table->string('card_cvc');
            $table->string('month');
            $table->string('year');
            $table->string('card_currency')->nullable();
            $table->string('is_primary')->nullable();
            $table->enum('is_approved',['approved','pending'])->default('pending');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('craditcards');
    }
}
