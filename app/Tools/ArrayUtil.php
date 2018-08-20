<?php
namespace App\Tools;


class ArrayUtil
{

    /**
     * @desc 将数组按照多个key进行排序
     * @return mixed
     *
     * @example:
     * $goodsList = [
     *      [
     *          'goods_id' => 10231
     *          'price' => 10,
     *          'goods_name' => '牙刷'
     *      ],
     *      [
     *          'goods_id' => 10130
     *          'price' => 35,
     *          'goods_name' => '充电宝'
     *      ],
     * ];
     * multiSort($goodsList, 'price', SORT_DESC, 'goods_id', SORT_ASC);
     */
    public function multiSort()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = [];
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    public function get($array, $key, $default = NULL)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        return $default;
    }

    public function getIntOrNull($array, $key)
    {
        if (isset($array[$key])) {
            return (int)$array[$key];
        }
        return NULL;
    }

    public function stringify(array $array)
    {
        foreach ($array as $k => &$v) {
            if (is_numeric($v)) {
                $v = (string)$v;
            }
        }
        return $array;
    }

    public function except(array $array, $except)
    {
        if (is_array($except)) {
            foreach ($except as $key => $value) {
                if (isset($array[$key])) {
                    unset($array[$key]);
                }
            }
        } else if ($except && isset($array[$except])) {
            unset($array[$except]);
        }
        return $array;
    }

    /**
     * 转数组
     * 字符串类型只识别逗号分割
     * @param string|\stdClass|object|array $value
     * @return array
     */
    public function toArray($value)
    {
        if (empty($value)) {
            return [];
        }
        if (is_string($value)) {
            return explode(',', trim($value, ','));
        } elseif (is_array($value)) {
            return $value;
        } elseif (is_object($value)) {
            return get_object_vars($value);
        } elseif (is_numeric($value)) {
            return explode(',', trim($value, ','));;
        } else {
            return [];
        }
    }
}