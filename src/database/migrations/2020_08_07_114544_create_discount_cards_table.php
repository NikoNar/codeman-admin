<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('code');
            $table->float('discount', 8,2);
            $table->string('cardholder_name')->nullable();
            $table->string('cardholder_phone')->nullable();
            $table->string('bonus')->nullable();
            $table->string('point')->nullable();
            $table->string('is_bonus_card')->nullable();
            $table->string('card_id')->nullable();
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
        Schema::dropIfExists('discount_cards');
    }
}
