<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 13:38
 */

namespace App\Tools\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Folder extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'folder';
    }
}