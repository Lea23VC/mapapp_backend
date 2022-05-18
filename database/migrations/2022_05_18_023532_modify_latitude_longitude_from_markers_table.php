<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ModifyLatitudeLongitudeFromMarkersTable extends Migration
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
            if (!Type::hasType('double')) {
                Type::addType('double', FloatType::class);
            }
            $table->double("latitude", 3, 12)->change();
            $table->double("longitude", 3, 12)->change();
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
            $table->float("latitude")->nullable()->change();
            $table->float("longitude")->nullable()->change();
        });
    }
}
