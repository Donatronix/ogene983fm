<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeProgrammeDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_programme_day', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('programme_id');
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('programme_day_id');
            $table->foreign('programme_day_id')->references('id')->on('programme_days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programme_programme_day');
    }
}
