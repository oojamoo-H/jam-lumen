<?php

namespace App\Tools\Network;


use App\Tools\ErrorHandler;
use App\Tools\Log\SeasLog;
use App\Tools\Authorization\Authorization;

class Curl
{
    const MODE_NORMAL = 1;
    const MODE_TEST = 2;
    const CURL_TIMEOUT = 3000;

    public static $mode = Curl::MODE_TEST;

    protected static function curl($url, $protocol, $params, $headerParams = [], $useSSL = FALSE, $paramsToJson = FALSE)
    {
        SeasLog::requestStart(LOG_PATH);
        $startAt = SeasLog::getMicroTime();
        $curl = curl_init();
        if (!$authHeader = Authorization::getAuthHeader($params)) {
            return FALSE;
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization' => $authHeader]);
//        if (self::$mode == Curl::MODE_TEST && PHP_OS == 'Darwin') {、、
//            curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8888');
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        }

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $useSSL);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $useSSL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);

        switch ($protocol) {
            case 'get':
                $url = self::buildUrl($url, $params);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json; charset=utf-8',
                ]);
                break;
            case 'put':
                $postJsonParams = json_encode($params, JSON_UNESCAPED_UNICODE);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($postJsonParams)]);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postJsonParams);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                break;
            case 'post':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                curl_setopt($curl, CURLINFO_CONTENT_TYPE, 'application/x-www-form-urlencoded');
                break;
            case 'postJson':
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params, JSON_UNESCAPED_UNICODE));
                break;
            case 'delete':
                if ($protocol == 'post') {
                    curl_setopt($curl, CURLOPT_POST, 1);
                } else {
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                }
                if (is_array($headerParams) && count($headerParams)) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerParams);
                }
                if ($paramsToJson) {
                    $postParams = json_encode($params);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($postParams)]);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postParams);
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
                }
                break;

            default :
                return "";
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
        }
        curl_close($curl);
        if (isset($put_data)) {
            fclose($put_data);
        }
        SeasLog::requestLog('curl', $url, strtoupper($protocol), $params, $data, SeasLog::caleCostTime($startAt));
        return $data;
    }

    protected static function buildUrl($baseUrl, $params, $encodeFlag = FALSE)
    {
        if (is_array($params) && !empty($params)) {
            $url = [];
            $url[] = $baseUrl;
            $url[] = '?';
            $query = http_build_query($params);
            $url[] = $encodeFlag ? urlencode($query) : $query;
            $url = implode("", $url);
        } else {
            $url = $baseUrl;
        }
        return $url;
    }

    /******************************************************************************
     * public functions
     ******************************************************************************/

    /**
     * @param $url
     * @param array $params
     * @param null $headerParams
     * @param bool $decode
     * @param bool $paramsToJson
     * @return mixed|null|string
     */
    public static function post($url, $params = [], $headerParams = NULL, $decode = TRUE, $paramsToJson = FALSE)
    {
        try {
            $result = self::curl($url, 'post', $params, $headerParams, FALSE, $paramsToJson);
            if ($decode) {
                return json_decode($result, TRUE);
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            SeasLog::saveError($e->getMessage(), 'curl_error', $e);
            return FALSE;
        }
    }

    /**
     * 以Application/json为内容类型提交
     * @param $url
     * @param array $params
     * @param null $headerParams
     * @param bool $decode
     * @param bool $paramsToJson
     * @return mixed|null|string
     */
    public static function postAppJson($url, $params = [], $headerParams = NULL, $decode = TRUE, $paramsToJson = FALSE)
    {
        try {
            $result = self::curl($url, 'postJson', $params, $headerParams, FALSE, $paramsToJson);
            if ($decode) {
                return json_decode($result, TRUE);
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            SeasLog::saveError('curl请求错误', 'curl_error', $e);
            return FALSE;
        }
    }

    /**
     * 以Application/json为内容类型提交
     * @param $url
     * @param array $params
     * @param null $headerParams
     * @param bool $decode
     * @param bool $paramsToJson
     * @return mixed|null|string
     */
    public static function put($url, $params = [], $headerParams = NULL, $decode = TRUE)
    {
        try {
            $result = self::curl($url, 'put', $params, $headerParams, FALSE, TRUE);
            if ($decode) {
                return json_decode($result, TRUE);
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            SeasLog::saveError('curl请求错误', 'curl_error', $e);
            return FALSE;
        }
    }

    /**
     * @param $url
     * @param array $params
     * @param null $headerParams
     * @param bool $decode
     * @return mixed|null
     */
    public static function postJSON($url, $params, $headerParams = NULL, $decode = TRUE)
    {
        return self::post($url, $params, $headerParams, $decode, TRUE);
    }

    public static function get($url, $params = [], $headerParams = NULL, $decode = TRUE)
    {
        try {
            $result = self::curl($url, 'get', $params, $headerParams, FALSE);
            if ($decode) {
                return json_decode($result, TRUE);
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            SeasLog::saveError('curl请求错误', 'curl_error', $e);
            return FALSE;
        }
    }

    public static function delete($url, $params = [], $headerParams = NULL, $decode = FALSE, $paramsToJson = FALSE)
    {
        try {
            $result = self::curl($url, 'delete', $params, $headerParams, FALSE, $paramsToJson);
            if ($decode) {
                return json_decode($result, TRUE);
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            SeasLog::saveError('curl请求错误', 'curl_error', $e);
            return FALSE;
        }
    }

    public static function sslPost($url, $params = [], $headerParams = NULL)
    {
        return self::curl($url, 'post', $params, $headerParams, TRUE);
    }

    public static function sslGet($url, array $params = [], $headerParams = NULL)
    {
        return self::curl($url, 'get', $params, $headerParams, TRUE);
    }

    public static function sslDelete($url, array $params = [], $headerParams = NULL)
    {
        return self::curl($url, 'delete', $params, $headerParams, TRUE);
    }
}

