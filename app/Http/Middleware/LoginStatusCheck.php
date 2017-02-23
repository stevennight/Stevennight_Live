<?php

namespace App\Http\Middleware;

use App\Custom\Functions;

class LoginStatusCheck{
    public function handle($request, \Closure $next){

        if($request->is('login')||$request->is('register')){
            if($request->session()->has('member')){
                return redirect() -> route('index');
            }
            return $next($request);
        }

        //如果用户登录了，刷新用户的信息。（确保封禁等行为的及时处理。）  refresh user's information when user has been login, for handle user immediatly like logout when users was baned.
        if(!$request->session()->has('member')){
            return redirect() -> route('index')->withErrors(['must_login' => trans('view.login.must_login')]);
        }

        return $next($request);
    }
}