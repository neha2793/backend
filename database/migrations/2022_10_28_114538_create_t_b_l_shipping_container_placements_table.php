<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBLShippingContainerPlacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Shipping_container_placements', function (Blueprint $table) {
            $table->id();
            $table->integer('SC_ID');
            $table->string('Wall_no')->nullable();
            $table->string('x_co')->nullable();
            $table->string('y_co')->nullable();
            $table->integer('Item_ID');
            $table->string('Item_type');
            $table->integer('status');
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
        Schema::dropIfExists('TBL_Shipping_container_placements');
    }
}
