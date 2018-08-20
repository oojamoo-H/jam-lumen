<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/19
 * Time: 14:19
 */

namespace App\Http\Controllers\Priv;

use App\Tools\ErrorHandler;
use App\Services\DemoService;
use Illuminate\Http\Request;
use PhpRedis;

class DemoController extends PrivateControllerBase
{
    public function getGoods(Request $request)
    {
        $param = $request->get('param');
        PhpRedis::set('test' , 'select db to 5');
        if(empty($param)){
            ErrorHandler::setErrorInfo('param参数不能为空！');
            return false;
        }

        $bizTest = new DemoService();
        $result = $bizTest->doAny($param);

        if(!$result){
            return false;
        }

        return $result;
    }
}