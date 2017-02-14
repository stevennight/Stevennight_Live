<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('remote_userid');
            $table->string('username',255);
            $table->integer('group')->default(0);
            $table->integer('baned')->default(0);
            $table->string('email',255);
            $table->integer('email_active')->default(0);
            $table->string('QQ',20);
            $table->integer('QQ_active')->default(0);
            $table->string('reg_address',255);
            $table->string('created_at',255);
            $table->string('updated_at',255);
            $table->string('last_login',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
