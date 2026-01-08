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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 20)->default('PENDING'); // PENDING, CONFIRMED, PREPARING, OUT_FOR_DELIVERY, READY_FOR_PICKUP, DELIVERED, COMPLETED, CANCELLED
            $table->string('order_type', 20)->default('PICKUP'); // DELIVERY, PICKUP, DINE_IN
            $table->string('phone_number', 20)->nullable();
            $table->string('delivery_address', 500)->nullable();
            $table->text('notes')->nullable();
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
