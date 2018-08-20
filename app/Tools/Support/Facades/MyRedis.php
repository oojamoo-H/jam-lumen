<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 17:37
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class MyRedis extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'myRedis';
    }
}