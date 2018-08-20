<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 11:15
 */

namespace App\Tools\Support\Facades;


use Illuminate\Support\Facades\Facade;

class ArrayUtil extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'arrayutil';
    }
}