<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone');
            $table->integer('role_id')->default(0);
            $table->string('photo')->nullable();
            $table->longText('about')->nullable();
            $table->longText('address')->nullable();
            $table->string('remember_token')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        DB::table('admins')->insert(array(
            'name' => 'Genius Ocean',
            'email' => 'superadmin@go.com',
            'phone' => '123456789',
            'role_id' => '1',
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
        Schema::dropIfExists('admins');
    }
}
