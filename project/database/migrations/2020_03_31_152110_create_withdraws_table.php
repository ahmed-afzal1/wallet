<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_email');
            $table->string('transaction_id');
            $table->double('amount')->default(0.00);
            $table->double('transaction_cost')->default(0.00);
            $table->string('transaction_currency')->default('usd');
            $table->string('account_number');
            $table->string('method_transaction_id')->nullable();  
            $table->enum('withdraw_status',['pending','complete'])->default('pending');  
            $table->date('expected_delivery_date')->nullable();  
            $table->string('status')->default('pending'); 
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
        Schema::dropIfExists('withdraws');
    }
}
