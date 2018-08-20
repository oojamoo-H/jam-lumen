<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/15
 * Time: 13:46
 */

namespace App\Http\Middleware;

use Closure;
use App\Tools\Log\SeasLog;


class AfterMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        SeasLog::accessLog($this->getPostData($request), $this->getQueryData($request), $response->getContent());
        return $response;
    }
}