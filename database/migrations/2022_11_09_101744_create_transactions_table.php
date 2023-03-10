<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Transactions', function (Blueprint $table) {
            $table->increments('T_ID');
            $table->integer('Order_ID');
            $table->integer('User_ID');
            $table->string('T_Amount');
            $table->string('Transaction_Token')->nullable();
            $table->string('Transaction_Date');
            $table->string('Status');
            $table->string('Payment_Method')->nullable();
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
        Schema::dropIfExists('TBL_Transactions');
    }
}
