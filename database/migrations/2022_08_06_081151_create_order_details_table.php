<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('id', 64)->primary()->unique();
            $table->string('order_id', 64);
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('food_id', 64);
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('no action');
            $table->tinyInteger('quantity');
            $table->string('total_price', 12);
            $table->string('status', 100);
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
        Schema::dropIfExists('order_details');
    }
};
