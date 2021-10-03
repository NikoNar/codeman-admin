<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('lang');
            $table->integer('parent_lang_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->enum('status', ['published','draft'] );
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('template')->nullable();
            $table->string('page_type')->default('page');
            $table->integer('order')->default(0);
            $table->string('meta-title')->nullable();
            $table->longText('meta-description')->nullable();
            $table->text('meta-keywords')->nullable();
            $table->unique(['slug', 'lang']);
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
        Schema::dropIfExists('pages');
    }
}
