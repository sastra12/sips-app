<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('customer_id');
            $table->string('customer_name', 100)->nullable(false);
            $table->string('customer_address', 100)->nullable(false);
            $table->integer('customer_neighborhood')->nullable(false);
            $table->integer('customer_community_association')->nullable(false);
            $table->integer('rubbish_fee')->default(0)->unsigned();
            $table->enum('customer_status', ['Rumah Tangga', 'Non Rumah Tangga']);
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
        Schema::dropIfExists('customers');
    }
}
