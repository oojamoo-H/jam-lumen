<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 17:34
 */

namespace App\Providers;

use App\Tools\MyRedis;
use Illuminate\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('myRedis', function(){
            return new MyRedis();
        });
    }
}