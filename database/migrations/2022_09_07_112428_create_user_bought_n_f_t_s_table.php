<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBoughtNFTSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User_bought_NFT', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('product_id');
            $table->string('from_wallet');
            $table->string('price');
            $table->string('string_price');
            $table->string('hash_token');
            $table->string('tokenID');
            $table->json('gasLimit');
            $table->json('gasPrice');
            $table->json('maxFeePerGas');
            $table->json('maxPriorityFeePerGas');
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
        Schema::dropIfExists('User_bought_NFT');
    }
}
