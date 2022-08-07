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
        Schema::create('listtype', function (Blueprint $table) {
            $table->string('id', 64)->primary()->unique();
            $table->string('code', 100)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('xml_data')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listtype');
    }
};
