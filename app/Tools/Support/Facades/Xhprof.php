<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/7
 * Time: 15:15
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Xhprof extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'xhprof';
    }
}