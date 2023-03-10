<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBLSlimeSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_slime_seats', function (Blueprint $table) {
            $table->increments('S_ID');
            $table->integer('User_ID');
            $table->string('name');
            $table->string('Description');
            $table->string('Price');
            $table->integer('product_id');
            $table->string('featured_image');
            $table->date('Date_created');
            $table->string('Status');
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
        Schema::dropIfExists('TBL_slime_seats');
    }
}
