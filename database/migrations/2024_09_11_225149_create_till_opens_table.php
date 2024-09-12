<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTillOpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('till_opens', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id")->nullable();
            $table->String("type")->nullable();
            $table->double("amount")->nullable();
            $table->date("date")->nullable();
            $table->double("five_thousand")->nullable();
            $table->double("one_thousand")->nullable();
            $table->double("five_hundred")->nullable();
            $table->double("one_hundred")->nullable();
            $table->double("fifty")->nullable();
            $table->double("twenty")->nullable();
            $table->double("ten")->nullable();
            $table->double("five")->nullable();
            $table->double("two")->nullable();
            $table->double("one")->nullable();
            $table->text("notes")->nullable();
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
        Schema::dropIfExists('till_opens');
    }
}
