<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_events', function (Blueprint $table) {
            $table->id();
            $table->string(     'Name'          );
            $table->text(       'Description'   );
            $table->dateTime(   'EvWhen', 0     );
            $table->dateTime(   'EvWhenEnd', 0  );
            $table->integer(    'id_User'       );
            $table->integer(    'EvType'        )->default(0);
            $table->string(     'EvWhere'       )->nullable();
            $table->integer(    'id_Subject'    )->nullable();
            $table->integer(    'id_Theme'      )->nullable();
            $table->string(     'Keywords'      )->nullable();
            $table->string(     'Questions'     )->nullable();
            $table->string(     'Homework'      )->nullable();
            $table->dateTime(   'WhenDoHW', 0   )->nullable();
            $table->string(     'Color'         )->nullable();
            $table->integer(    'id_Group'      )->nullable();
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
        Schema::dropIfExists('about_events');
    }
}
