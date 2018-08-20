<?php

namespace App\Tools\RedLock;

class RedLock
{
    protected $retryDelay;

    protected $retryCount;

    protected $clickDriftFactory = 0.01;

    protected $quorum;

    protected $servers = [];

    protected $instances = [];

    public function __construct(array $servers, $retryDelay = 200, $retryCount = 3)
    {
        $this->servers = $servers;
        $this->retryDelay = $retryDelay;
        $this->retryCount = $retryCount;
        $this->quorum = min(count($servers), (count($servers) / 2 + 1));
    }

    public function lock(){
        $this->initInstance();
        $token = uniqid();
        $retry = $this->retryCount;

        do{
            $n = 0;
            $startTime = microtime(TRUE);
        }while($retry > 0);

        return FALSE;
    }

    public function unlock(){}

    protected function initInstance(){
        if(!$this->instances){
            $this->instances = array_map([$this, 'instance'], $this->servers);
        }
    }

    protected function instance($server){
        return new \Redis($server);
    }

    protected function lockInstance($instance, $resource, $token, $ttl){
        return $instance->set($resource, $token, 'PX', $ttl, 'NX');
    }

    protected function unlockInstance($instance, $resource, $token){
        $script = '
            if redis.call("GET", KEYS[1]) == ARGV[1] then
                return redis.call("DEL", KEYS[1])
            else
                return 0
            end
        ';

        return $instance->eval($script, 1, $resource, $token);
    }
}