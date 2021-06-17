<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTimeToFromInProgrammeTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programme_times', function (Blueprint $table) {
            $table->renameColumn('time', 'from');
            $table->unsignedBigInteger('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programme_times', function (Blueprint $table) {
            $table->dropColumn('to');
            $table->renameColumn('from', 'time');
        });
    }
}
