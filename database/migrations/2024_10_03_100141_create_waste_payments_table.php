<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_payments', function (Blueprint $table) {
            $table->uuid('payment_id');
            $table->primary('payment_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('month_payment', 10)->nullable(false);
            $table->string('year_payment', 10)->nullable(false);
            $table->integer('amount_due')->nullable(false);
            $table->enum('status', ['LUNAS', 'BELUM DIBAYAR']);
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waste_payments');
    }
}
