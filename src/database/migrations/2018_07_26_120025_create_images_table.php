<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
           $table->increments('id');
           $table->text('original_name');
           $table->string('filename')->unique();
           $table->text('alt')->nullable();
           $table->integer('width')->nullable();
           $table->integer('height')->nullable();
           $table->text('file_size')->nullable();
           $table->text('file_type')->nullable();
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
        Schema::dropIfExists('images');
    }
}
