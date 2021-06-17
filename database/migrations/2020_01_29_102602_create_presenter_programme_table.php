<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresenterProgrammeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presenter_programme', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('presenter_id')->unsigned()->index();
            $table->foreign('presenter_id')->references('id')->on('presenters')->onDelete('cascade');
            $table->bigInteger('programme_id')->unsigned()->index();
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
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
        Schema::dropIfExists('presenter_programme');
    }
}
