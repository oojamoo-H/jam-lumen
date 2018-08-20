<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 13:43
 */

namespace App\Http\Middleware;

class BaseMiddleware
{

    protected function getQueryData($request)
    {
        return $request->isMethod('get') ? $request->all() : '';
    }

    protected function getPostData($request)
    {
        return $request->isMethod('post') ? $request->all() : '';
    }
}