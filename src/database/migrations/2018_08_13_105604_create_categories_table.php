<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('parent_lang_id')->nullable();
            $table->text('content')->nullable();
            $table->string('title')->nullable();
            $table->integer('level')->nullable();
            $table->integer('node')->nullable();
            $table->string('slug');
            $table->string('type')->nullable();
            $table->integer('order')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('lang')->nullable();
            $table->string('status')->default('published');
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
        Schema::dropIfExists('cm_categories');
    }
}
