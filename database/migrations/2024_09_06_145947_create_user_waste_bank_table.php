<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWasteBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_waste_bank', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->integer("waste_id")->nullable(false)->unsigned();
            $table->primary(["user_id", "waste_id"]);
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("waste_id")->references("waste_bank_id")->on("waste_banks");
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
        Schema::dropIfExists('user_waste_bank');
    }
}
