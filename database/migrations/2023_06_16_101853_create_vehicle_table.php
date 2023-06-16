<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration_number');
            $table->integer('vehicle_category_id', false, true);
            $table->integer('discount_card_id', false, true)->nullable();
            $table->timestamp('entered_on')->useCurrent();

            // Foreign keys
            $table->foreign('vehicle_category_id')->references('id')->on('vehicle_categories');
            $table->foreign('discount_card_id')->references('id')->on('discount_cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle');
    }
}
