<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('alt_email')->nullable();
            $table->string('acc_type')->default('personal');
            $table->string('remember_token')->nullable();
            $table->string('verify_token')->nullable();
            $table->timestamp('email_verified_at')->nullable(); 
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('business_name')->nullable();
            $table->string('api_key')->nullable();
            $table->double('current_balance')->default(0);
            $table->string('website')->nullable();
            $table->longText('about')->nullable();
            $table->string('photo')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('fax')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('postalcode')->nullable();
            $table->string('featured')->nullable();
            $table->string('language')->nullable();
            $table->string('default_currency')->default(1);
            $table->double('transaction_limit')->default(0);
            $table->double('transaction_limit_amount')->default(0.00);
            $table->tinyInteger('is_referral')->default(0); 
            $table->string('referrar')->nullable(); 
            $table->double('referral_commission_amount')->default(0.00); 
            $table->enum('referral_commission_amount_status',['pending','paid'])->default('pending'); 
            $table->tinyInteger('account_status')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
        
        DB::table('users')->insert(array(
            'name' => 'Test User',
            'email' => 'user@user.com',
            'username' => 'user100',
            'acc_type' => 'personal',
            'phone' => '123456789',
            'photo' => 'assets/userpanel/images/user/user-1.jpg',
            'gender' => 'male',
            'country' => 'Bangladesh',
            'state' => 'Dhaka',
            'city' => 'Dhaka',
            'postalcode' => '1230',
            'password' => Hash::make('123456'),
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
        Schema::dropIfExists('users');
    }
}
