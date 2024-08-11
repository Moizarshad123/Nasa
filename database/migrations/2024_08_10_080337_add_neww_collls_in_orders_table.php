<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewwColllsInOrdersTable extends Migration
{

    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->String('payment_method')->after('outstanding_amount')->nullable();
            $table->Integer('received_by')->after('payment_method')->nullable();
            $table->Integer('amount_received')->after('received_by')->default(0);
            $table->Integer('amount_charged')->after('amount_received')->default(0);
            $table->Integer('cash_back')->after('amount_charged')->default(0);
            $table->Integer('remaining_amount')->after('cash_back')->default(0);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('received_by');
            $table->dropColumn('amount_received');
            $table->dropColumn('amount_charged');
            $table->dropColumn('cash_back');
            $table->dropColumn('remaining_amount');
        });
    }
}
