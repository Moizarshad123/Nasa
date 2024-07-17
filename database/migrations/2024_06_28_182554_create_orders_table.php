<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->Integer('category_id');
            $table->Integer('user_id');
            $table->Integer('assign_to')->nullable();
            $table->String("order_number");
            $table->String('customer_name')->nullable();
            $table->String('phone')->nullable();
            $table->String('no_of_persons')->nullable();
            $table->date('creating_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->String('delivery_time')->nullable();
            $table->String('order_nature')->nullable();
            $table->Integer('order_nature_amount')->nullable(); 
            $table->tinyInteger('is_email')->nullable();
            $table->Integer('email_amount')->nullable();
            $table->Text('emails')->nullable(); 
            $table->String('order_type')->nullable();
            $table->String('re_order_number')->nullable();
            $table->Integer('amount')->nullable();
            $table->Integer('grand_total')->nullable();
            $table->Integer('discount_amount')->nullable();
            $table->Integer('net_amount')->nullable();
            $table->Integer('outstanding_amount')->nullable(); 
            $table->text('remarks')->nullable();
            $table->String('status')->default('Active');
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
        Schema::dropIfExists('orders');
    }
}
