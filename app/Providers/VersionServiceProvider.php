<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/17
 * Time: 11:56
 */

namespace App\Providers;


use App\Tools\Version;
use Illuminate\Support\ServiceProvider;

class VersionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('version', function(){
            return new Version();
        });
    }

}