<?php

namespace Core\Tools;

class SeasLog extends \Seaslog
{
    static $requestStartTime;

    static function requestStart($path = '')
    {
        self::setRequestStartTime();
        parent::setBasePath($path);
    }

    static function getMicroTime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }

    static function setRequestStartTime()
    {
        self::$requestStartTime = self::getMicroTime();
    }

    static function caleCostTime($startTime = 0)
    {
        if (empty($startTime)) {
            $startTime = self::$requestStartTime;
        }
        return round((self::getMicroTime() - $startTime) * 1000, 3);  //返回毫秒级时间差;
    }

    /**
     * @param $postData
     * @param $getData
     * @param $response
     */
    static function accessLog($postData, $getData, $response)
    {
        $message = 'URI:' . $_SERVER['REQUEST_URI'] . '   COST_TIME:' . self::caleCostTime() . 'ms   POST:' . self::toString($postData) . '  GET:' . self::toString($getData) . '   RESPONSE:' . self::toString($response);
        parent::info($message, [], 'access');
    }

    static function localRequestErrorLog($model, $url, $postData, $resultData, $costTime)
    {
        $resultDataString = is_array($resultData) ? json_encode($resultData, JSON_UNESCAPED_UNICODE) : $resultData;
        $postDataString = is_array($postData) ? json_encode($postData, JSON_UNESCAPED_UNICODE) : $postData;
        $message = 'URL:' . $url . '  COST_TIME:' . $costTime . 'ms   POST:' . $postDataString . '   RESULT:' . $resultDataString;
        parent::error($message, array(), $model);
    }

    static function localRequestLog($model, $url, $postData, $resultData, $costTime)
    {
        $resultDataString = is_array($resultData) ? json_encode($resultData, JSON_UNESCAPED_UNICODE) : $resultData;
        $postDataString = is_array($postData) ? json_encode($postData, JSON_UNESCAPED_UNICODE) : $postData;
        $message = 'URL:' . $url . '  COST_TIME:' . $costTime . 'ms   POST:' . $postDataString . '   RESULT:' . $resultDataString;
        parent::info($message, array(), $model);
    }

    static function requestLog($model, $url, $postData, $resultData, $costTime)
    {
        $resultDataString = is_array($resultData) ? json_encode($resultData, JSON_UNESCAPED_UNICODE) : $resultData;
        $postDataString = is_array($postData) ? json_encode($postData, JSON_UNESCAPED_UNICODE) : $postData;
        $message = 'URL:' . $url . '  COST_TIME:' . $costTime . 'ms   POST:' . $postDataString . '   RESULT:' . $resultDataString;
        parent::info($message, array(), $model);
    }

    static public function saveError($errorMessage, $model, $param = [])
    {
        if (!empty($model)) {
            $model = str_replace('::', DS, $model);
            $model = str_replace('\\', DS, $model);
        }
        $message = 'URI:' . $_SERVER['REQUEST_URI'] . PHP_EOL .
            '  PARAM:' . self::toString($param) . PHP_EOL .
            '  ERROR_CONTENT:' . PHP_EOL . $errorMessage;
        parent::error($message, [], $model);
    }

    static public function saveInfo($message, $module, $content = array())
    {
        parent::info(self::toString($message), $content, $module);
    }

    static public function saveDebugLog($message)
    {
        parent::error($message, [], 'debug');
    }

    static public function toString($message)
    {
        if (is_object($message) || is_array($message)) {
            return json_encode($message, TRUE);
        } else {
            return $message;
        }
    }
}