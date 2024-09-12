<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWasteBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_banks', function (Blueprint $table) {
            $table->integer('waste_bank_id')->autoIncrement()->unsigned();
            $table->string('waste_name', 100)->nullable(false);
            $table->integer('village_id')->unsigned()->nullable(false);

            $table->foreign('village_id')->references('village_id')->on('villages');
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
        Schema::dropIfExists('waste_banks');
    }
}
