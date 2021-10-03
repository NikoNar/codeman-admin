<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->float('price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->string('sku')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            // $table->longText('images')->nullable();
            $table->text('thumbnail')->nullable();
            $table->integer('category_id')->nullable();
            $table->enum('status', ['pending', 'published','draft', 'archive', 'deleted', 'schedule']);
            $table->enum('type',['simple','variation', 'group', 'downloadble']);
            $table->integer('allow_order')->default(1);
            $table->integer('stock_count')->nullable();
            $table->integer('stock_status')->default(1);
            $table->integer('brand_id')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->string('lang');
            $table->integer('parent_lang_id')->nullable();
            $table->integer('order')->default(0);
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_og_title')->nullable();
            $table->text('meta_og_image')->nullable();
            $table->text('meta_og_description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
