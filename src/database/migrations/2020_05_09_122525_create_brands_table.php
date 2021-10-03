<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('first_letter');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->text('thumbnail')->nullable();
            // $table->text('banner')->nullable();
            $table->text('logo')->nullable();
            $table->enum('status', ['published','draft', 'pending', 'archive', 'deleted', 'schedule']);
            $table->string('lang');
            $table->integer('parent_lang_id')->nullable();
            $table->integer('order')->default(0);
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_og_title')->nullable();
            $table->text('meta_og_image')->nullable();
            $table->text('meta_og_description')->nullable();
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
        Schema::dropIfExists('brands');
    }
}
