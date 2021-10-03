<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('api_id')->nullable();
            $table->string('api_product_id')->nullable();
            // $table->integer('product_option_id');
            $table->float('price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('secondary_thumbnail')->nullable();
            $table->integer('stock_count')->nullable();
            $table->integer('stock_status')->default(1);
            $table->integer('order')->default(0);
            $table->enum('status', ['pending', 'published','draft', 'archive', 'deleted', 'schedule']);
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
        Schema::dropIfExists('variations');
    }
}
