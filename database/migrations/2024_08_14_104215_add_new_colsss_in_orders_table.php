<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsssInOrdersTable extends Migration
{

    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->Integer('refund_amount')->after('remaining_amount')->default(0);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refund_amount');
        });
    }
}
