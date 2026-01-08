<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 15)->unique();
            $table->string('password');
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('email_verified')->default(false);
            $table->date('date_of_birth')->nullable();
            $table->string('role', 20)->default('USER');
            $table->string('push_token', 200)->nullable();
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
        Schema::dropIfExists('users');
    }
}
