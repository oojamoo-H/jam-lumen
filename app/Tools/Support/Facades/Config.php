<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 12:57
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'config';
    }
}