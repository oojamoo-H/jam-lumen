<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/2
 * Time: 09:41
 */

namespace App\Tools\Redis;

use Illuminate\Support\ServiceProvider;
use Config;

class PhpRedisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('phpredis', function(){
            $config = Config::get('database.redis');
            return (new RedisFactory(strtolower($config['client']), $config))->connection() ;
        });

        $this->app->bind('phpredis.connection', function($app){
            return $app['PhpRedis']->connection();
        });
    }
}