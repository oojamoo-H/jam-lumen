<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/1
 * Time: 15:54
 */

namespace App\Tools\Redis\Connections;

use Illuminate\Contracts\Redis\Connection as ConnectionContract;

class PhpRedisConnection extends Connection implements ConnectionContract
{

    protected $client;

    protected $lockPrefix = 'lock:';

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function get($key)
    {
        $result = $this->client->get($key);

        return $result !== FALSE ? $result : NULL;
    }


    public function set($key, $value, $ttl = -1)
    {
        $value = $this->toString($value);

        if ($ttl < 0) {
            return $this->client->set($key, $value);
        } else if ($ttl == 0) {
            $ttl = strtotime(date('Y-m-d 23:59:59')) - time();
        }

        return $this->client->setex($key, $ttl, $value);
    }

    public function setNx($key, $value)
    {
        return $this->client->setNx($key, $value);
    }

    public function mGet($keys)
    {
        return array_map(function ($value) {
            $value === FALSE ? $this->toArray($value) : NULL;
        }, $this->client->mGet($keys));
    }

    public function incr($key, $ttl = -1)
    {
        if ($result = $this->client->incr($key)) {
            if ($ttl == 0) {
                $this->expire($key, strtotime(date('Y-m-d 23:59:59')) - time());
            } elseif ($ttl > 0) {
                $this->expire($key, $ttl);
            }
        }

        return $result;
    }

    public function incrBy($key, $value = 1, $ttl = -1)
    {
        if ($result = $this->client->incrBy($key, $value)) {
            if ($ttl == 0) {
                $this->expire($key, strtotime(date('Y-m-d 23:59:59')) - time());
            } elseif ($ttl > 0) {
                $this->expire($key, $ttl);
            }
        }

        return $result;
    }

    public function hSet($key, $field, $value)
    {
        $value = $this->toString($value);
        return $this->client->hSet($key, $field, $value);
    }

    public function hMset($key, ...$dictionary)
    {
        if (count($dictionary) == 1) {
            $dictionary = $dictionary[0];
        } else {
            $input = collect($dictionary);

            $dictionary = $input->nth(2)->combine($input->nth(2, 1))->toArray();
        }

        return $this->command('hmset', [$key, $dictionary]);
    }

    public function hExists($key, $field = NULL)
    {
        return $this->client->hExists($key, $field);
    }

    public function hGetAll($key)
    {
        if (!$result = $this->client->hGetAll($key)) {
            return $result;
        }

        $result = collect($result)->map(function ($value) {
            return $this->toArray($value);
        })->toArray();

        return $result;
    }

    public function hVals($key)
    {
        if (!$result = $this->client->hVals($key)) {
            return $result;
        }

        return collect($result)->map(function ($value) {
            return $this->toArray($value);
        })->toArray();

    }

    public function hIncrBy($key, $field, $incr = 1)
    {
        return $this->client->hIncrBy($key, $field, $incr);
    }

    public function hKeys($key)
    {
        return $this->client->hKeys($key);
    }

    public function hLen($key)
    {
        return $this->client->hLen($key);
    }

    public function sAdd($key, ...$dictionary)
    {
        if (is_array(end($dictionary))) {
            foreach (array_shift($dictionary) as $member) {
                $dictionary[] = $this->toString($member);
            }
        }

        return $this->executeRaw(array_merge(['sAdd', $key], $dictionary));
    }

    public function sCard($key)
    {
        return $this->client->sCard($key);
    }

    public function sIsMember($key, $value)
    {
        return $this->client->sIsMember($key, $value);
    }

    public function sMembers($key)
    {
        if ($result = $this->client->sMembers($key)) {
            $result = collect($result)->map(function ($value) {
                return $this->toArray($value);
            })->toArray();
        }

        return $result;
    }

    public function sRem($key, ...$dictionary)
    {
        if (is_array(end($dictionary))) {
            foreach (array_shift($dictionary) as $member) {
                $dictionary[] = $this->toString($member);
            }
        }

        return $this->executeRaw(array_merge(['sRem', $key], $dictionary));
    }

    public function sMove($sourceKey, $destinationKey, $value)
    {
        return $this->command('sMove', [$sourceKey, $destinationKey, $value]);
    }

    /**
     * 传入字典类型 [value => score]
     *
     * @param $key
     * @param array ...$dictionary
     * @return mixed
     */
    public function zAdd($key, ...$dictionary)
    {
        if (is_array(end($dictionary))) {
            foreach (array_pop($dictionary) as $member => $score) {
                $dictionary[] = $score;
                $dictionary[] = $member;
            }
        }

        return $this->executeRaw(array_merge(['zadd', $key], $dictionary));
    }

    public function zRem($key, ...$dictionary)
    {
        if (is_array(end($dictionary))) {
            foreach (array_pop($dictionary) as $member) {
                $dictionary[] = $member;
            }
        }

        return $this->executeRaw(array_merge(['zRem', $key], $dictionary));
    }

    public function zCard($key)
    {
        return $this->client->zCard($key);
    }

    public function zCount($key, $min, $max)
    {
        return $this->command('zCount', [$key, $min, $max]);
    }

    public function zRangeByScore($key, $min = 0, $max, $options = [])
    {
        if (isset($options['limit'])) {
            $options['limit'] = [
                $options['limit']['offset'],
                $options['limit']['count'],
            ];
        }

        return $this->command('zRangeByScore', [$key, $min, $max, $options]);
    }

    public function zRevRangeByScore($key, $min = 0, $max = -1, $options = [])
    {
        if (isset($options['limit'])) {
            $options['limit'] = [
                $options['limit']['offset'],
                $options['limit']['count'],
            ];
        }

        return $this->command('zRevRangeByScore', [$key, $min, $max, $options]);
    }

    public function zRange($key, $min = 0, $max = -1)
    {
        return $this->client->zRange($key, $min, $max);
    }

    public function zRevRange($key, $min = 0, $max = -1)
    {
        return $this->client->zRevRange($key, $min, $max);
    }

    public function zScore($key, $member)
    {
        return $this->client->zScore($key, $member);
    }

    public function zIncrBy($key, $member, $incr = 1)
    {
        return $this->client->zIncrBy($key, $member, $incr);
    }

    public function del($key)
    {
        return $this->client->del($key);
    }

    public function exists($key)
    {
        return $this->client->exists($key);
    }

    public function expire($key, $time)
    {
        return $this->client->expire($key, $time);
    }

    public function expireAt($key, $time)
    {
        return $this->client->expireAt($key, $time);
    }

    public function ttl($key)
    {
        return $this->client->ttl($key);
    }

    public function time()
    {
        return $this->client->time();
    }

    protected function toString($var)
    {
        if (is_string($var) || is_int($var)) {
            return $var;
        }

        if (is_array($var) || is_object($var)) {
            return json_encode($var, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function toArray($var)
    {
        $var = json_decode($var, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($result = json_last_error() == JSON_ERROR_NONE) {
            return $var;
        }

        return $var;
    }

    public function executeRaw(array $parameters)
    {
        return $this->command('rawCommand', $parameters);
    }

    public function __call($method, $parameters)
    {
        $method = strtolower($method);

        return parent::__call($method, $parameters);
    }

}