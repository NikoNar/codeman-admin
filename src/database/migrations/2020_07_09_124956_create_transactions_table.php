<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->text('payment_id')->nullable();
            $table->string('merchant');
            $table->integer('merchant_id')->nullable();
            $table->float('amount', 8, 2);
            $table->string('currency');
            $table->string('currency_code');
            $table->string('description')->nullable();
            $table->string('back_url')->nullable();
            $table->longText('additional_data')->nullable();
            $table->integer('card_holder_id')->nullable();
            $table->enum('status', ['complited','declined','failed','other'])->default('pending');
            // $table->enum('status', ['succeeded','canceled','pending','waiting_for_capture'])->default('pending');
            $table->text('status_message')->nullable();
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
