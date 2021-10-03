<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsIntoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('notes')->nullable()->after('short_description');
            $table->float('sale_percent', 8, 2)->nullable()->after('sale_price');
            $table->string('sex')->nullable()->after('content');
            $table->string('country')->nullable()->after('content');
            // $table->integer('product_api_id')->nullable()->after('id');
        });
        
        Schema::table('variations', function (Blueprint $table) {
            $table->float('sale_percent', 8, 2)->nullable()->after('sale_price');
            // $table->integer('variation_api_id')->nullable()->after('id');
            $table->string('sku')->after('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('sale_percent');
            $table->dropColumn('sex');
            $table->dropColumn('country');
        });
        Schema::table('variations', function (Blueprint $table) {
            $table->dropColumn('sale_percent');
            $table->dropColumn('sku');
        });
    }
}
