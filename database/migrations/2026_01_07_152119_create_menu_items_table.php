<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('menu_categories')->onDelete('set null');
            $table->string('title', 150);
            $table->string('title_si', 150)->nullable();
            $table->string('title_ta', 150)->nullable();
            $table->text('description')->nullable();
            $table->text('description_si')->nullable();
            $table->text('description_ta')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image_url', 500)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('display_order')->nullable();
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
        Schema::dropIfExists('menu_items');
    }
}
