<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigGlobalWebsite extends Migration
{
    /**
     * Run the migrations.
     *
     * version: 1.1
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('config_global_website')){
            Schema::create('config_global_website', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name',255);
                $table->string('email',255);
                $table->integer('oauth_client_id');
                $table->text('oauth_url');
                $table->string('oauth_client_secret',255);
                $table->text('roomcover_prefix');
                $table->text('roomcover_default');
                $table->string('chatserver',255);
                $table->integer('danmakuSpeed');
            });
            \Illuminate\Support\Facades\DB::table('config_global_website')->insert([
                'name' => '网站名称',
                'email' => 'youreamil@admin.com',
                'oauth_client_id' => 0,
                'oauth_url' => 'oauth_server_url',
                'oauth_client_secret' => 'you_client_secret',
                'roomcover_prefix' => '/files/',
                'roomcover_default' => 'default_file',
                'chatserver' => 'chat_server_address',
                'danmakuSpeed' => 5,
            ]);
        }

        //Update Database
        Schema::table('config_global_website',function($table){
           $table->integer('mustVerifyEmail');
           $table->integer('mustVerifyQQ');
           $table->integer('mustVerifyEmail_when_createroom');
           $table->integer('mustVerifyQQ_when_createroom');
        });
        \Illuminate\Support\Facades\DB::table('config_global_website')->update([
            'mustVerifyEmail' => 1,
            'mustVerifyQQ' => 1,
            'mustVerifyEmail_when_createroom' => 1,
            'mustVerifyQQ_when_createroom' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_global_website',function($table){
            if(Schema::hasColumn('config_global_website','mustVerifyEmail')){
                $table->dropColumn('mustVerifyEmail');
                $table->dropColumn('mustVerifyQQ');
                $table->dropColumn('mustVerifyEmail_when_createroom');
                $table->dropColumn('mustVerifyQQ_when_createroom');
            }
        });
    }
}
