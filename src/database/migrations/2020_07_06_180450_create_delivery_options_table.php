<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('zone_allowed')->nullable();
            $table->string('zone_disallowed')->nullable();
            $table->float('price', 8, 2)->default(0);
            $table->float('price_tax', 8, 2)->default(0);
            $table->float('price_fee', 8, 2)->default(0);
            $table->enum('status', ['published', 'disabled']);
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
        Schema::dropIfExists('delivery_options');
    }
}
