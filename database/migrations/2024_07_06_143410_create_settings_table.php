<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->Integer('urgent_amount_big')->nullable();
            $table->Integer('expose_amount_big')->nullable();
            $table->Integer('media_amount_big')->nullable();
            $table->Integer('urgent_amount_small')->nullable();
            $table->Integer('expose_amount_small')->nullable();
            $table->Integer('media_amount_small')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
