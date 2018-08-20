<?php

namespace App\Tools\Redis;

use Illuminate\Contracts\Redis\Factory;
use InvalidArgumentException;

class RedisFactory implements Factory
{
    protected $_driver;

    protected $_connections;

    protected $_config;

    protected const PHP_REDIS_DRIVER = 'phpredis';

    protected const P_REDIS_DRIVER = 'predis';


    public function __construct($driver, array $config)
    {
        $this->_driver = $driver;
        $this->_config = $config;
    }

    public function connection($name = null)
    {
        $name = $name ?: 'default';

        if (isset($this->_connections[$name])){
            return $this->_connections[$name];
        }

        return $this->_connections[$name] = $this->resolve($name);
    }

    public function resolve($name)
    {

        $options = $this->_config['options'] ?? [];

        if ($this->_config['cluster']){
            return $this->connector()->connectCluster($this->_config['clusters'][$name], $options);
        }

        if (isset($this->_config[$name])){
            return $this->connector()->connect($this->_config[$name], $options);
        }

        throw new InvalidArgumentException("Redis connection [{$name}] not configured.");
    }

    protected function connector()
    {
        switch ($this->_driver)
        {
            case static::PHP_REDIS_DRIVER:
                return new Connectors\PhpRedisConnector;
            case static::P_REDIS_DRIVER:
                return new Connectors\PredisConnector;
        }
    }


    public function connections()
    {
        return $this->_connections;
    }
}