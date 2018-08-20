<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/3
 * Time: 16:12
 */

namespace App\Http\Middleware;

use Closure;
use App\Tools\Log\SeasLog;

class PublicBeforeMiddleware extends BeforeMiddleware
{
    public function handle($request, Closure $next)
    {
        SeasLog::requestStart(LOG_PATH);
        return $next($request);
    }
}