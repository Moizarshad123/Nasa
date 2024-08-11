<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_numbers', function (Blueprint $table) {
            $table->id();
            $table->String("type")->nullable();
            $table->String("order_number")->nullable();
            $table->String("is_used")->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('order_numbers');
    }
}
