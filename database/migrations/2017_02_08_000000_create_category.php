<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * version: 1.0
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('category')){
            Schema::create('category', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name',20);
            });
        }else{

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
