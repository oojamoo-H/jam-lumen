<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/3
 * Time: 15:48
 */

namespace App\Tools\Authorization;


use function Couchbase\fastlzCompress;
use Mockery\Exception;

/**
 * 权限签名类
 * 验签处理在中间件中，PrivateMiddleware和PublicMiddleware
 * 内部调用生成签名已封装在Curl中，请求头部需添加Authorization
 * 如果有新的业务系统，需要在AuthKey中添加相关系统key
 * Public 验签目前暂无
 * Class Authorization
 * @package App\Tools\Authorization
 */
class Authorization
{
    /**
     * 外部调用类型
     */
    public const SIGN_TYPE_PUBLIC = 'pb';

    /**
     * 内部调用类型
     */
    public const SIGN_TYPE_PRIVATE = 'pv';

    /**
     * 签名前缀
     */
    public const AUTHORIZATION_PREFIX = 'Bear ';

    protected const SUB_STRING_START = -1;

    protected const SUB_STRING_LENGTH = 22;

    /**
     * 签名算法
     * 算法：请求参数+模块secretKey，根据键名asc2排序后进行base64和md5加密
     *      截取字符串长度为：从当前时间戳最后一位数字开始截取，截取长度为总长度22-当前时间戳最后一位数字
     * @param $params
     * @param null $time
     * @return bool|string
     */
    public static function encType($params, $time = NULL)
    {
        $params['secretKey'] = AuthKey::Key($params['model']);
        $time = $time ?? time();
        ksort($params);
        $params = http_build_query($params) ;
        $string = md5(base64_decode($params));
        $start = substr($time, -1);
        return substr($string, $start, static::SUB_STRING_LENGTH - $start);
    }

    /**
     * 获取Http请求头部
     * @param array $params
     * @return bool|string
     */
    public static function getAuthHeader(array $params)
    {
        try{
            $sign = static::encType($params);
            return static::AUTHORIZATION_PREFIX . base64_encode($sign . time());
        } catch (Exception $e){
            return FALSE;
        }

    }

    /**
     * 验证签名
     * @param $signature
     * @param $source
     * @param $params
     * @return bool
     */
    public static function verifySignature($signature, $source, $params)
    {
        switch ($source) {
            case static::SIGN_TYPE_PRIVATE:
                return static::checkPrivateSignature($signature, $params);
            case static::SIGN_TYPE_PUBLIC:
                return static::checkPublicSignature($signature, $params);
            default:
                return FALSE;
        }
    }

    /**
     * 验证服务端签名
     * @param $signature
     * @param array $params
     * @return bool
     */
    protected static function checkPrivateSignature($signature, array $params)
    {
        if (! $signature){
            return FALSE;
        }
        $arraySignature = explode(' ', $signature);

        if ($arraySignature[0] != rtrim(static::AUTHORIZATION_PREFIX)){
            return FALSE;
        }
        $authTime = substr(base64_decode($arraySignature[1]), -10);
        $authSign = substr(base64_decode($arraySignature[1]), 0, -10);
        return $authSign == static::encType($params, $authTime);
    }

    protected static function checkPublicSignature($authStr, array $params)
    {
        return FALSE;
    }

}