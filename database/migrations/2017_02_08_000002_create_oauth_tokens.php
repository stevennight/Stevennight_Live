<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->default(0);
            $table->string('session_id',255);
            $table->string('access_token',255);
            $table->string('expires_at',255);
            $table->string('update_token',255);
            $table->string('update_token_expires_at',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_tokens');
    }
}
