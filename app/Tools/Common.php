<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/17
 * Time: 09:49
 */

namespace App\Tools;


class Common
{
    /**
     * 多个数组的笛卡尔积
     * @return array
     */
    public static function combineDika()
    {
        $data = func_get_args();
        $data = current($data);
        $result = array();
        $arr1 = array_shift($data);
        foreach ($arr1 as $key => $item) {
            $result[] = array($item);
        }

        foreach ($data as $key => $item) {
            $result = self::combineArray($result, $item);
        }
        return $result;
    }

    /**
     * 两个数组的笛卡尔积
     * @param $arr1
     * @param $arr2
     * @return array
     */
    public static function combineArray($arr1, $arr2)
    {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    static public function gmtIso8601($time)
    {
        $dtStr = date("c", $time);
        $myDateTime = new \DateTime($dtStr);
        $expiration = $myDateTime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    static function randomString(int $len = 32, bool $withSpecChar = TRUE): string
    {
        $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        $specialChars = 'ILUVOSloqr019';
        if ($withSpecChar) {
            $chars .= $specialChars;
        }
        $maxPos = strlen($chars);
        $string = '';
        for ($i = 0; $i < $len; $i++) {
            $string .= $chars[mt_rand(0, $maxPos)];
        }
        return $string;
    }

    /**
     * 手机号码隐私保护
     * @param $string
     * @param int $start_index
     * @param int $end_len
     * @return mixed
     */
    static public function maskMp($string, $start_index = 3, $end_len = 4)
    {
        if (!preg_match('/(\d){11}/', $string)) {
            return $string;
        }
        $allLen = strlen($string);
        $len = 5;
        if ($allLen > $start_index + $end_len) {
            $len = $allLen - $end_len - $start_index;
        }
        return substr_replace($string, str_pad('*', $len, '*'), $start_index, $len);
    }

    /**
     * 处理Curl返回的结果
     * 根据返回内容及返回码，做本地处理
     * @param $result
     * @param string $noResponseError
     * @return array|bool
     */
    public static function stripResult($result, $noResponseError = '')
    {
        if (!$result) {
            ErrorHandler::setErrorInfo($noResponseError);
            return FALSE;
        }

        if ($result['code'] != 200) {
            if (!isset($result['msg'])) {
                $result['msg'] = $result['message'];
            }
            ErrorHandler::setErrorInfo($result['code'], $result['msg']);
            return FALSE;
        }

        if(isset($result['data'])){
            return $result['data'];
        }

        return FALSE;
    }
}