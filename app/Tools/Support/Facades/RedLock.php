<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/16
 * Time: 14:10
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class RedLock extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'redlock';
    }
}