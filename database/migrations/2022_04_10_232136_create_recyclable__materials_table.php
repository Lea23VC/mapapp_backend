<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecyclableMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recyclable__materials', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
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
        Schema::dropIfExists('recyclable__materials');
    }
}
