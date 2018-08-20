<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/7
 * Time: 10:15
 */

namespace App\Tools;


class ErrorCode
{
    public const HTTP_CODE = [
        '404' => 'Not Found',
        '401' => 'Unauthorized',
        '403' => 'Forbidden',
        '500' => 'Internal Server Error',
        '502' => 'Internal Server Error',
    ];
}