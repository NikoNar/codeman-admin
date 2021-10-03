<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('name')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('phone')->after('last_name')->nullable();
            $table->string('dob')->after('phone')->nullable();
            $table->string('gender')->after('dob')->nullable();
            $table->integer('receive_newsletter')->after('gender')->nullable();
            $table->integer('receive_sms')->after('receive_newsletter')->nullable();
            $table->string('provider')->after('receive_sms')->nullable();
            $table->string('provider_id')->after('provider')->nullable();
            $table->string('loyalty_card')->after('provider_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone');
            $table->dropColumn('dob');
            $table->dropColumn('gender');
            $table->dropColumn('receive_newsletter');
            $table->dropColumn('receive_sms');
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');
            $table->dropColumn('loyalty_card');
        });
    }
}
