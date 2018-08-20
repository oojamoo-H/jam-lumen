<?php

namespace App\Tools\Redis\Connectors;

use Illuminate\Support\Arr;
use App\Tools\Redis\Connections\PhpRedisConnection;
use App\Tools\Redis\Connections\PhpRedisClusterConnection;

class PhpRedisConnector
{
    public function connect(array $config, array $options){
        return new PhpRedisconnection($this->createClient(array_merge(
            $config, $options, Arr::pull($config, 'options', [])
        )));
    }

    public function connectCluster(array $config, array $options)
    {
        $options = array_merge($options, Arr::pull($config, 'option', []));

        return new PhpRedisClusterConnection($this->createRedisClusterInstance(
            array_map([$this, 'buildClusterConnectionString'], $config), $options
        ));
    }

    protected function createClient(array $config)
    {
        return tap(new \Redis, function($client) use ($config){
            $this->establishConnection($client, $config);

            if (! empty($config['password'])) {
                $client->auth($config['password']);
            }

            if (! empty($config['database'])) {
                $client->select($config['database']);
            }

            if (! empty($config['prefix'])) {
                $client->setOption(\Redis::OPT_PREFIX, $config['prefix']);
            }

            if (! empty($config['read_timeout'])) {
                $client->setOption(\Redis::OPT_READ_TIMEOUT, $config['read_timeout']);
            }
        });
    }

    protected function establishConnection($client, array $config)
    {
        $persistent = $config['persistent'] ?? false;
        $parameters = [
            $config['host'],
            $config['port'],
            Arr::get($config, 'timeout', 0.0),
            $persistent ? Arr::get($config, 'persistent_id', null) : null,
            Arr::get($config, 'retry_interval', 0),
        ];

        if (version_compare(phpversion('redis'), '3.1.3', '>=')) {
            $parameters[] = Arr::get($config, 'read_timeout', 0.0);
        }

        $client->{($persistent ? 'pconnect' : 'connect')}(...$parameters);
    }

    protected function buildClusterConnectionString($server)
    {
        return $server['host'].':'.$server['port'];
    }

    protected function createRedisClusterInstance(array $servers, array $options)
    {
        return new \RedisCluster(NULL, array_values($servers));
    }
}