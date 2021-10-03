<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index();
            $table->string('fingerprint')->unique()->index();
            $table->integer('user_id')->nullable()->index();
            $table->integer('device_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->string('client_ip')->nullable();
            $table->integer('referer_id')->nullable();
            $table->string('cookie_id')->nullable();
            $table->integer('geoip_id')->nullable();
            $table->boolean('is_robot')->defalut(0);

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
        Schema::dropIfExists('sessions');
    }
}
