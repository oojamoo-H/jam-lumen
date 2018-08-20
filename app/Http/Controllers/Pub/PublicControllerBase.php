<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/6/12
 * Time: 下午4:53
 */

namespace App\Http\Controllers\Pub;

use App\Http\Controllers\ControllerBase;

class PublicControllerBase extends ControllerBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dispatchNow($job, $handler = NULL)
    {

    }

    public function dispatch($job)
    {

    }
}