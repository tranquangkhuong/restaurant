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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 64)->primary()->unique();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->string('role', 100);
            $table->boolean('active')->default(1);
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('address')->nullable();
            $table->text('xml_data')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
