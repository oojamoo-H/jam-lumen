<?php
namespace App\Services;

use App\Tools\ErrorHandler;

class DemoService
{
    public function doAny($param)
    {
        if ($param != 'yes'){
            ErrorHandler::setErrorInfo('参数测试错误');
            return FALSE;
        }

        return ['key' => 'Hello World'];

    }
}