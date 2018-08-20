<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/14
 * Time: 18:20
 */

namespace Core;

use Laravel\Lumen\Application;

class CoreApplication extends Application
{
    use CoreRoutesRequests;

    /**
     * 批量注册Service Providers;
     * @param array $providers
     */
    public function massRegister($providers)
    {
        if (is_array($providers)){
            foreach ($providers as $p){
                parent::register($p);
            }
        }
    }
}