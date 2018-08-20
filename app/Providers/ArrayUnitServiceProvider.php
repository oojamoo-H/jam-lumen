<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 10:57
 */

namespace App\Providers;

use App\Tools\ArrayUtil;
use Illuminate\Support\ServiceProvider;

class ArrayUnitServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('arrayutil', function(){
            return new ArrayUtil();
        });
    }
}