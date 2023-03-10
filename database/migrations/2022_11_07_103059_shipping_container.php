<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShippingContainer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Shipping_container', function (Blueprint $table) {
            $table->increments('Sc_ID');
            $table->string('Name');
            $table->string('Description');
            $table->string('Featured_Image');
            $table->string('User_ID');
            $table->string('Status')->default(1);
            $table->integer('Visit_count')->default(0);
            $table->integer('Current_count')->nullable();
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
        Schema::dropIfExists('TBL_Shipping_container');
    }
}
