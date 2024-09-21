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
            $table->double("five_thousand")->default(0);
            $table->double("one_thousand")->default(0);
            $table->double("five_hundred")->default(0);
            $table->double("one_hundred")->default(0);
            $table->double("fifty")->default(0);
            $table->double("twenty")->default(0);
            $table->double("ten")->default(0);
            $table->double("five")->default(0);
            $table->double("two")->default(0);
            $table->double("one")->default(0);
            $table->text("notes")->default(0);
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
