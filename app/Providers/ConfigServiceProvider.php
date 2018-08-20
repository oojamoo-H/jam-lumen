<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 13:05
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Contracts\Container\Container;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * 在ServiceProviders都注册完成后加载config文件中的配置项(数组)至内存
     */
    public function boot()
    {
        foreach ($this->getConfigFiles($this->app) as $key => $path) {
            $this->app->make('config')->set($key, require $path);
        }
    }

    /**
     * 获取配置文件
     * @param Container $app
     * @return array
     */
    protected function getConfigFiles(Container $app)
    {
        $files = [];
        foreach (Finder::create()->files()->name('*.php')->in($app->getConfigurationPath()) as $file)
        {
            //获取配置文件对应的key
            $key = basename($file->getRealPath(), '.php');
            $files[$key] = $file->getRealPath();
        }
        return $files;
    }

    /**
     * 获取配置文件的嵌套目录,将config/aa/bb/cc.php的嵌套目录返回形如aa.bb.cc作为key
     * @param SplFileInfo $file
     * @param Container $app
     * @return string
     */
    protected function getConfigNestingKey(SplFileInfo $file, Container $app)
    {
        //应用的配置目录,加trim函数是为了确保$appConfigPath<=$directory
        $appConfigPath = trim($app->getConfigurationPath(), DIRECTORY_SEPARATOR);
        //当前配置文件的目录
        $directory = dirname($file->getRealPath());
        //补充key,配置文件的key一般是: config/app.php 则 app => require(config/app.php),配置文件会有目录嵌套
        $key = basename($file->getRealPath(), '.php');
        //判断是否有目录嵌套
        /*if($nesting = trim(str_replace($appConfigPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nesting = str_replace(DIRECTORY_SEPARATOR, '.', $nesting);
            $key = $nesting . '.' . $key;
        }*/
        return $key;
    }
}