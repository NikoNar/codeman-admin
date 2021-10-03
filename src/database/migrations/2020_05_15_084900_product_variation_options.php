<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductVariationOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_options', function (Blueprint $table) {
            $table->integer('variation_id');
            $table->integer('product_option_group_id');
            $table->integer('product_option_id');
            $table->integer('product_id');
        });
        // ALTER TABLE `variations` ADD `api_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
        // ALTER TABLE `variations` ADD `api_product_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variation_options');
    }
}
