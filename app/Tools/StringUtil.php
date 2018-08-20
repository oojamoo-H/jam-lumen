<?php

namespace Core\Tools;

class StringUtil
{
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

    public static function length($string = NULL): int
    {
        preg_match_all("/./us", $string, $match);
        return count($match[0]);
    }

    /**
     * 格式化字符串列表
     * 例：入参 2,4,2,3,1  返回：1,2,3,4
     * @param $listValue
     * @param string $glue
     * @return string
     */
    public static function formatList($listValue, $glue = ','): string
    {
        $power = explode($glue, $listValue);
        if (is_array($power)) {
            asort($power);
            return trim(implode($glue, array_unique($power)), ',');
        } else {
            return '';
        }

    }
}