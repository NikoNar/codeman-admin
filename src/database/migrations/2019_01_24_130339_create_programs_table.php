<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('language_id');
            $table->integer('parent_lang_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('duration');
            $table->string('frequency');
            $table->string('price');
            $table->string('start_date');
            $table->enum('status', ['published','draft'] );
            $table->longText('description')->nullable();
            $table->string('thumbnail')->nullable();
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
        Schema::dropIfExists('programs');
    }
}
