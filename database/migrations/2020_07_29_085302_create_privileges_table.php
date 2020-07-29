<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Privileges;

class CreatePrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ru_Name',64);
            $table->string('eng_Name',64);
            $table->timestamps();
        });
        Privileges::create(['ru_Name' => 'Пользователь', 'eng_Name' => 'User']); 
        Privileges::create(['ru_Name' => 'Староста',     'eng_Name' => 'Headman']);
        Privileges::create(['ru_Name' => 'Модер',        'eng_Name' => 'Moder']);
        Privileges::create(['ru_Name' => 'Админ',        'eng_Name' => 'Admin']); // 4
        Privileges::create(['ru_Name' => 'Преподаватель','eng_Name' => 'Teacher']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privileges');
    }
}
