<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('type');
            $table->integer('language_id');
            $table->integer('parent_lang_id')->nullable();
            $table->enum('status', ['published','draft'] );
            $table->string('thumbnail')->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->string('meta-title')->nullable();
            $table->longText('meta-description')->nullable();
            $table->text('meta-keywords')->nullable();
            $table->unique(['slug', 'language_id']);
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
        Schema::dropIfExists('resources');
    }
}
