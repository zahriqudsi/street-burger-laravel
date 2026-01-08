<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('phone_number', 15);
            $table->string('guest_name', 100);
            $table->integer('guest_count');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->text('special_requests')->nullable();
            $table->string('status', 20)->default('PENDING'); // PENDING, CONFIRMED, CANCELLED, COMPLETED
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
        Schema::dropIfExists('reservations');
    }
}
