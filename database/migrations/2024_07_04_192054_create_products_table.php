<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->Integer("product_category_id")->nullable();
            $table->String("title")->nullable();
            $table->Integer("qty")->default(0);
            $table->Integer("premium_standard_cost")->default(0);
            $table->Integer("studio_lpm_total")->default(0);
            $table->Integer("media_lpm_total")->default(0);
            $table->Integer("studio_frame_total")->default(0);
            $table->Integer("media_frame_total")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
