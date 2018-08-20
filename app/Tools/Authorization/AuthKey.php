<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/6
 * Time: 10:01
 */

namespace App\Tools\Authorization;

use InvalidArgumentException;

class AuthKey
{
    protected const PROMOTION_CENTER_KEY = '1be68e037ef7e22afc387150bb94bf29';

    protected const GOODS_CENTER_KEY = 'ad7068b7e3c1c708ec985402baa00b0a';

    protected const COUPON_CENTER_KEY = 'fc93b040320d2d454e48971c5ecf0141';

    protected const MESSAGE_CENTER_KEY = 'e51a447fbe7df8040d4136de4576bd09';

    protected const PROMOTION_MODEL = 'PROMOTION';

    protected const GOODS_MODEL = 'GOODS';

    protected const COUPON_MODEL = 'COUPON';

    protected const MESSAGE_MODEL = 'MESSAGE';

    public static function Key($model)
    {

        switch (strtoupper($model)){
            case static::PROMOTION_MODEL:
                return static::PROMOTION_CENTER_KEY;
            case static::GOODS_MODEL:
                return static::GOODS_CENTER_KEY;
            case static::COUPON_MODEL:
                return static::COUPON_CENTER_KEY;
            case static::MESSAGE_MODEL:
                return static::MESSAGE_CENTER_KEY;
            default:
                throw new InvalidArgumentException("Model {$model} not exists");
        }
    }
}