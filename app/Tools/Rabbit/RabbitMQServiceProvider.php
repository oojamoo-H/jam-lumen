<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/17
 * Time: 11:45
 */

namespace App\Tools\Rabbit;

use Illuminate\Support\ServiceProvider;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('rabbitmq', function(){
            return new RabbitMQ();
        });
    }
}