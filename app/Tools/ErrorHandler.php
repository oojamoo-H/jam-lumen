<?php
namespace App\Tools;

use App\Services\Log\SeasLog;

class ErrorHandler
{
    const ERROR_CODE_TOKEN_INVALID = -401;
    const ERROR_CODE_DUPLICATE_ENTRY = -1062;
    const ERROR_CODE_PARAM_VALIDATE = -1001;
    const ERROR_CODE_RECORD_NOT_EXIST = -1004;
    const ERROR_CODE_SIGN_INVALID = -102;
    const ERROR_CODE_SIGN_ERROR = -103;
    const ERROR_CODE_DEFAULT = 0;

    static $message = '';
    static $code = self::ERROR_CODE_DEFAULT;
    static $data = [];

    static function exception($exception, array $param = NULL, int $level = 1)
    {

        $logContent = '--------------------------------------------' . PHP_EOL .
            'Time: ' . date('Y-m-d H:i:s') . PHP_EOL .
            'Message: (' . $exception->getCode() . ')  ' . $exception->getMessage() . PHP_EOL .
            'File: ' . $exception->getFile() . '(' . $exception->getLine() . ')' . PHP_EOL .
            'Param: ' . var_export($param, TRUE) . PHP_EOL .
            'Trace: ' . $exception->getTraceAsString() . PHP_EOL . PHP_EOL . PHP_EOL;
        $errorModel = 'console_error';

        if (Di::getDefault()->get('config')->debug) {
            //输出错误信息
            if (is_null($_SERVER['SHELL'])) {
                $errorModel = 'www_error';
                header('HTTP/1.1 500 Internal Server Error');
                echo str_replace(PHP_EOL, '<br />', $logContent);
            }
        } else {
            if (is_null($_SERVER['SHELL']) && $level == E_ERROR) {
                $errorModel = 'www_error';
                header('HTTP/1.1 500 Internal Server Error');
                echo '服务器内部错误！';
                SeasLog::requestLog($_POST, $_GET);
            }
        }
        SeasLog::saveError($logContent, $errorModel, $param);
        if ($level == E_ERROR) {
            exit();
        }
    }

    static function saveDebugLog($message, $fn, $fnParam)
    {
        $logContent = PHP_EOL . '--------------------------------------------' . PHP_EOL .
            'Time:' . date('Y-m-d H:i:s') . PHP_EOL .
            'Message:' . $message . PHP_EOL .
            'Function:' . $fn . PHP_EOL .
            'Trace:' . var_export($fnParam, TRUE) . PHP_EOL;
        SeasLog::saveDebugLog($logContent);
    }

    static function displayAndBreak()
    {
    }

    /**
     * 设置错误信息
     */
    static public function setErrorInfo()
    {
        $args = func_get_args();
        if (count($args) < 1) {
            self::$message = '未知错误';
            self::$code = self::ERROR_CODE_DEFAULT;
        } elseif (count($args) == 1) {
            if (is_numeric($args[0])) {
                self::$message = '未知错误';
                self::$code = (int)$args[0];
            } else {
                self::$message = $args[0];
                self::$code = self::ERROR_CODE_DEFAULT;
            }
        } else {
            if (is_numeric($args[0])) {
                self::$message = $args[1];
                self::$code = $args[0];
            } else {
                self::$message = $args[0];
                self::$code = $args[1];
            }
        }
    }

    /**
     * 清空错误信息
     */
    static public function clearErrorInfo()
    {
        self::$code = 0;
        self::$message = '';
        self::$data = [];
    }
}