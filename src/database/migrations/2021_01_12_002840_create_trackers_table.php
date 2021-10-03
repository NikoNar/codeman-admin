<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracker_views', function (Blueprint $table) {
            $table->id();
            $table->string('resource')->nullable(); //action.controller
            $table->integer('resource_id')->nullable(); // parameter value
            $table->string('parameter_key')->nullable(); // parameter name
            $table->string('parameter_value')->nullable(); // parameter value
            $table->string('model')->nullable(); // obj model name with namespace

            $table->text('url'); // full
            $table->integer('user_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            // $table->string('is_quick_view');
            $table->text('referral_url')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('geoip_id')->nullable();
            $table->string('lang')->nullable();
            $table->time('time')->nullable();
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
        Schema::dropIfExists('tracker_views');
    }
}
