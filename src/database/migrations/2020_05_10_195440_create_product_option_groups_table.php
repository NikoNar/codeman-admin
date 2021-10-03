<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['select', 'radio', 'colorpicker', 'image']);
            $table->enum('status', ['published','draft', 'pending', 'archive', 'deleted', 'schedule']);
            $table->boolean('show_on_website')->default(1);
            $table->string('lang');
            $table->integer('parent_lang_id')->nullable();
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('product_option_groups');
    }
}
