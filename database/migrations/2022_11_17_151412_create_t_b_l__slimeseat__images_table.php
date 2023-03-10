<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBLSlimeseatImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Slimeseat_Images', function (Blueprint $table) {
            $table->id();
            $table->integer('S_ID');
            $table->string('Image_path');
            $table->string('Remarks')->nullable();
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
        Schema::dropIfExists('TBL_Slimeseat_Images');
    }
}
