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
    Route::get('room/{roomid}', 'RoomController@showRoom')->name('room');
});

Route::get('error','Controller@error')->name('error');