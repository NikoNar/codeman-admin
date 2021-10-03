<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('session_id')->nullable();
            
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_address_building')->nullable();
            $table->string('billing_address_apartment')->nullable();
            $table->string('billing_zip_code')->nullable();

            $table->boolean('ship_to_another_person')->default(1);
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_address_building')->nullable();
            $table->string('shipping_address_apartment')->nullable();
            $table->string('shipping_zip_code')->nullable();

            $table->text('order_note')->nullable();

            $table->string('shipping_type'); // 'delivery', 'pickup'
            $table->integer('pickup_location_id')->nullable();
            $table->float('shipping_price', 8,2)->default(0);
            $table->float('shipping_tax', 8,2)->default(0);
            $table->float('shipping_fee', 8,2)->default(0);

            $table->string('payment_type');
            $table->float('payment_tax', 8,2)->default(0);
            $table->float('payment_fee', 8,2)->default(0);

            $table->string('discount_card')->nullable();
            $table->float('discount_percent', 8,2)->default(0);

            $table->float('subtotal', 8,2);
            $table->float('total', 8,2);

            $table->string('promo_code')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('orders');
    }
}
