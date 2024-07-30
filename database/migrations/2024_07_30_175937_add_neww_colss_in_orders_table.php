<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewwColssInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->String("is_background")->after("emails")->default(0);
            $table->tinyInteger("bg_qty")->nullable("is_background");
            $table->String("bg_color")->after("bg_qty")->nullable();
            $table->double("bg_amount")->after("bg_color")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_background');
            $table->dropColumn('bg_qty');
            $table->dropColumn('bg_color');
            $table->dropColumn('bg_amount');

        });
    }
}
