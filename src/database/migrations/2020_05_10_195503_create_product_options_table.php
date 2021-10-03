<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->integer('product_option_group_id');
            $table->string('value');
            $table->string('name')->nullable();
            $table->enum('status', ['published','draft', 'pending', 'archive', 'deleted', 'schedule']);
            // $table->string('lang');
            // $table->integer('parent_lang_id')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
        // ALTER TABLE `product_options` ADD `api_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`, ADD INDEX (`api_id`);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_options');
    }
}
