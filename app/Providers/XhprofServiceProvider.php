<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Tools\Xhprofs\XhprofRuns;

class XhprofServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton("xhprof", function () {
            return new XhprofRuns();
        });
    }
}