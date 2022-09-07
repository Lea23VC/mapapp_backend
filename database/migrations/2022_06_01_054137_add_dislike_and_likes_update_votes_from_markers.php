<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDislikeAndLikesUpdateVotesFromMarkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markers', function (Blueprint $table) {
            //
            $table->renameColumn('votes', 'likes');
            $table->integer("dislikes")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markers', function (Blueprint $table) {
            //
            $table->renameColumn('likes', 'votes');
            $table->dropColumn("dislikes");
        });
    }
}
