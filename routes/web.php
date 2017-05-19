<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['ConfigToSession']],function() {
    //首页  index.
    Route::get('/', 'UserController@index')->name('index');

    //授权回调地址   authorization recall address.
    Route::get('oauth/recall/{authcode}', 'OAuthController@recall');
    //通过Token从用户服务器上更新用户信息
    Route::post('oauth/user/refresh', 'OAuthController@refreshUserInfo')->name('refreshUserInfo')->middleware('LoginStatusCheck');

    Route::get('login', 'UserController@login')->name('login')->middleware('LoginStatusCheck');
    Route::get('logout', 'UserController@logout')->name('logout')->middleware('LoginStatusCheck');

    //修改房间信息
    Route::post('room/edit', 'UserController@roomedit')->name('roomedit')->middleware('LoginStatusCheck');

    //播放页
    Route::get('room/{roomid}', 'RoomController@showRoom')->name('room')->where(['roomid'=>'[0-9]+']);
    Route::get('room/owner/{roomid}','RoomController@roomOwner')->name('roomOwner')->middleware('LoginStatusCheck')->where(['roomid'=>'[0-9]+']);
    Route::post('room/owner/{roomid}/toggleIndex','RoomController@toggleIndex')->name('roomToggleIndex')->where(['roomid'=>'[0-9]+']);
    Route::get('room/owner/edit','RoomController@showRoomEditSingle')->name('roomEditSingle')->middleware('LoginStatusCheck');
});

Route::get('error','Controller@error')->name('error');
/*Route::get('test',function(){
    $host = "127.0.0.1";
    $port = 1935;
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create    socket\n"); // 创建一个Socket

    $connection = socket_connect($socket, $host, $port) or die("Could not connet server\n");    //  连接
    socket_write($socket, "hello socket") or die("Write failed\n"); // 数据传送 向服务器发送消息
    //while ($buff = @socket_read($socket, 1024, PHP_NORMAL_READ)) {
    $buff = @socket_read($socket, 1024, PHP_NORMAL_READ);
        echo("Response was:" . $buff . "\n");
    //}
    socket_close($socket);
});*/