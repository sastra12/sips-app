<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWasteEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_entries', function (Blueprint $table) {
            $table->bigIncrements('entry_id');
            $table->integer('waste_organic')->unsigned()->nullable(false);
            $table->integer('waste_anorganic')->unsigned()->nullable(false);
            $table->integer('waste_residue')->unsigned()->nullable(false);
            $table->integer('waste_total')->nullable(false);
            $table->integer('waste_id')->unsigned()->nullable(false);

            $table->foreign('waste_id')->references('waste_bank_id')->on('waste_banks');
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
        Schema::dropIfExists('waste_entries');
    }
}
