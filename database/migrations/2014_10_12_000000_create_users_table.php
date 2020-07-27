<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
            $table->string('Surname',64)->nullable();
            $table->string('Login',64)->unique();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->integer('id_Group')->nullable();
            $table->dateTime('LastLogin',0)->nullable();
            $table->string('ShopInfo',64)->nullable();
            $table->integer('Coins')->default(100);
            $table->integer('id_Interface')->nullable();
            $table->integer('id_City')->nullable();
            $table->integer('id_Country')->nullable();
            $table->integer('Privilege')->default(0);
            $table->string('Avatar',128)->default('images/none.jpg');
        });
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
