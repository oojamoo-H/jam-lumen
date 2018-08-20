<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/3
 * Time: 16:12
 */

namespace App\Http\Middleware;

use App\Tools\ErrorHandler;
use Closure;
use App\Tools\Log\SeasLog;
use App\Tools\Authorization\Authorization;

class PrivateBeforeMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        $params = $request->input();
        SeasLog::requestStart(LOG_PATH);
        $authSign = $request->header('Authorization');
        if (! Authorization::verifySignature($authSign,  Authorization::SIGN_TYPE_PRIVATE, $params)){
            ErrorHandler::setErrorInfo(401, 'Unauthorized');
            return FALSE;
        }

        return $next($request);
    }
}