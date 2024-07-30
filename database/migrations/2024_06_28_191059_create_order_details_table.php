<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->Integer("order_id")->nullable();
            $table->String("expose")->nullable();
            $table->String("size")->nullable();
            $table->String("country")->nullable();
            $table->Integer("qty")->nullable();
            $table->Integer("print_cost")->nullable();
            $table->Integer("studio_LPM_total")->nullable();
            $table->Integer("media_LPM_total")->nullable();
            $table->Integer("studio_frame_total")->nullable();
            $table->Integer("media_frame_total")->nullable();
            $table->Integer("total")->nullable();
            $table->Text("remarks")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
