<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 10:49
 */

namespace App\Providers;

use App\Tools\Folder;
use Illuminate\Support\ServiceProvider;

class FolderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('folder', function(){
            return new Folder();
        });
    }
}