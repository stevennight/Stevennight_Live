<?php

namespace App\Http\Middleware;

use App\Custom\Functions;
use App\Database\Users;

class ConfigToSession{
    public function handle($request, \Closure $next){
        if(!Functions::getConfigGlobalWebsite()){
            //读取网站配置失败  fail to load config of website.
            return redirect()->route('error')->with(['error' => 'Can not get any config from datebase.']);
        }

        if($request->session()->has('member')){
            $db_user = Users::where('id','=',$request->session()->get('member.userid'))->first();
            Functions::refreshUserInfoIntoSession($db_user);
        }

        return $next($request);
    }
}