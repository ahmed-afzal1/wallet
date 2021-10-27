<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_type');
            $table->string('transaction_id')->unique();
            $table->string('is_api')->nullable();
            $table->double('amount')->default(0.00);
            $table->double('transaction_cost')->default(0.00);
            $table->string('transaction_currency')->default('usd');
            $table->string('costpay')->default('receiver');  
            $table->string('sender')->nullable();
            $table->string('sender_is')->nullable();
            $table->string('receiver')->nullable();  
            $table->string('receiver_is')->nullable();  
            $table->string('method')->nullable();
            $table->string('method_transaction_id')->nullable();  
            $table->tinyInteger('transaction_refund_with_cost')->default(0);  
            $table->tinyInteger('refund_status')->default(0);  
            $table->longText('referral_url')->nullable();  
            $table->date('expected_delivery_date')->nullable();  
            $table->longText('reference')->nullable();  
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
        Schema::dropIfExists('transactions');
    }
}
