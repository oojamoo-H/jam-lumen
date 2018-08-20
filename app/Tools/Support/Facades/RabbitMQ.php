<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/17
 * Time: 11:46
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class RabbitMQ extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'rabbitmq';
    }
}