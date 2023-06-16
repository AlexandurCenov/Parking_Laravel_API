<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('category', 10);
            $table->tinyInteger('number_of_places');
            $table->smallInteger('day_tariff');
            $table->smallInteger('night_tariff');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_categories');
    }
}
