<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_email');
            $table->double('amount')->unsinged();
            $table->string('currency');
            $table->string('transaction_id');
            $table->string('method');
            $table->string('method_transaction_id')->nullable();
            $table->string('craditcard_id')->nullable();
            $table->enum('deposit_status',['pending','complete'])->default('pending');
            $table->string('status')->default(1);
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
        Schema::dropIfExists('deposits');
    }
}
