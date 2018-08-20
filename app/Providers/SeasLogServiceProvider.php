<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 10:35
 */

namespace App\Providers;

use App\Tools\Log\SeasLog;
use Illuminate\Support\ServiceProvider;

class SeasLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('slog', function(){
            return new SeasLog();
        });
    }
}