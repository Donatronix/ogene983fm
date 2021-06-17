<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlbumArtToSongOfTheWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_of_the_weeks', function (Blueprint $table) {
            $table->string('album_art', 255);;
            $table->string('song', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_of_the_weeks', function (Blueprint $table) {
            $table->dropColumn('album_art');;
            $table->dropColumn('song');
        });
    }
}
