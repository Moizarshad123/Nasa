<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->Integer("order_id")->nullable();
            $table->String("payment_method")->nullable();
            $table->Integer("received_by")->default(0);
            $table->Integer("amount_received")->default(0);
            $table->Integer("amount_charged")->default(0);
            $table->Integer("cash_back")->default(0);
            $table->Integer("outstanding_amount")->default(0);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
