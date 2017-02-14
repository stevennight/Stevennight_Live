<?php

namespace App\Http\Middleware;

use App\Custom\Functions;

class ConfigToSession{
    public function handle($request, \Closure $next){
        if(!Functions::getConfigGlobalWebsite()){
            //读取网站配置失败  fail to load config of website.
            return redirect()->route('error')->with(['error' => 'Can not get any config from datebase.']);
        }

        return $next($request);
    }
}