<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Orders', function (Blueprint $table) {
            $table->increments('Order_ID');
            $table->integer('User_ID');
            $table->string('Shipping_FirstName');
            $table->string('Shipping_lastName');
            $table->string('Shipping_address1');
            $table->string('Shipping_address2');
            $table->string('Shipping_city');
            $table->string('Shipping_state');
            $table->string('Shipping_Zipcode');
            $table->string('country');
            $table->string('Order_total');
            $table->string('Order_date');
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
        Schema::dropIfExists('TBL_Orders');
    }
}
