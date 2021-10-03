<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('title')->nullable();
            $table->integer('variation_id')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->integer('qty')->default(1);
            $table->string('variation_option_type')->nullable();
            $table->string('variation_option_group')->nullable();
            $table->string('variation_option_value')->nullable();
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
        Schema::dropIfExists('order_items');
    }
}
