<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * version: 1.0
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('rooms')){
            Schema::create('rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('userid');
                $table->string('roomname',25);
                $table->text('roomintro',25);
                $table->integer('category');
                $table->text('rtmpurl');
                $table->string('streamkey',255);
                $table->text('coverurl');
                $table->integer('isindex');
                $table->string('roomkey',255);
                $table->integer('cooperation');
                $table->string('otherroomkey',255);
                $table->string('created_at',255);
                $table->string('updated_at',255);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
