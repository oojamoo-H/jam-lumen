<?php
namespace Core\Tools;


class ClientInfo
{
    static function ip($returnLong = TRUE)
    {
        $strIp = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strstr($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $x = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $_SERVER['HTTP_X_FORWARDED_FOR'] = trim(end($x));
            }
            if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $strIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $strIp = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (empty($strIp)) {
            if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['REMOTE_ADDR'])) {
                $strIp = $_SERVER['REMOTE_ADDR'];
            }
        }
        if ($returnLong) {
            $return = empty($strIp) ? 0 : bindec(decbin(ip2long($strIp)));
        } else {
            $return = $strIp;
        }
        return $return;
    }
}