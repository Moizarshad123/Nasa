<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSmallRatesTable extends Migration
{
 
    public function up()
    {
        Schema::create('order_small_rates', function (Blueprint $table) {
            $table->id();
            $table->Integer("category_id")->nullable();
            $table->Integer("qty")->nullable();
            $table->Integer("expose_rate")->nullable();
            $table->Integer("reorder_rate")->nullable();
            $table->Integer("media_rate")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_small_rates');
    }
}
