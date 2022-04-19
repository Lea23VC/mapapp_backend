<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title")->nullable();
            $table->string("imgURL")->nullable();
            $table->string("status")->nullable();
            $table->boolean("availability")->nullable();
            $table->integer("points")->nullable();
            $table->float("latitude")->nullable();
            $table->float("longitude")->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string("address_number")->nullable();
            $table->string("address_street")->nullable();
            $table->string("commune")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("country")->nullable();

            $table->boolean('PE')->default(0);
            $table->boolean('PET')->default(0);
            $table->boolean('PVC')->default(0);
            $table->boolean("aluminium")->default(0);
            $table->boolean("batteries")->default(0);
            $table->boolean("cardboard")->default(0);
            $table->boolean("cellphones")->default(0);
            $table->boolean("glass")->default(0);
            $table->boolean("oil")->default(0);
            $table->boolean("otherPapers")->default(0);
            $table->boolean("otherPlastics")->default(0);
            $table->boolean("paper")->default(0);
            $table->boolean("tetra")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markers');
    }
}
