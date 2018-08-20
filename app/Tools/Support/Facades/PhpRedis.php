<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/2
 * Time: 09:29
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class PhpRedis extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'phpredis';
    }
}