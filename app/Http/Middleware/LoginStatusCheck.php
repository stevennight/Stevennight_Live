<?php

namespace App\Http\Middleware;

class LoginStatusCheck{
    public function handle($request, \Closure $next){

        if($request->is('login')||$request->is('register')){
            if($request->session()->has('member')){
                return redirect() -> route('index');
            }
            return $next($request);
        }

        if(!$request->session()->has('member')){
            return redirect() -> route('index')->withErrors(['must_login' => trans('view.login.must_login')]);
        }

        return $next($request);
    }
}