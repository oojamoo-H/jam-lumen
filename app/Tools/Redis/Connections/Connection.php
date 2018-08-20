<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/7/26
 * Time: 15:14
 */

namespace App\Tools\Redis\Connections;

use Closure;

abstract class Connection
{
    protected $client;


    /**
     * Subscribe to a set of given channels for messages.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @return void
     */
    public function subscribe($channels, Closure $callback){}

    /**
     * Subscribe to a set of given channels with wildcards.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @return void
     */
    public function psubscribe($channels, Closure $callback){}


    public function command($method, array $parameters = [])
    {
        return $this->client->{$method}(...$parameters);
    }

    public function __call($method, $parameters)
    {
        return $this->command($method, $parameters);
    }
}