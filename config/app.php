<?php
return [
    /**
     * Facades配置
     */
    'alias' => [
        App\Tools\Support\Facades\Config::class => 'Config',
        App\Tools\Support\Facades\ArrayUtil::class => 'ArrayUtil',
        App\Tools\Support\Facades\Folder::class => 'Folder',
        App\Tools\Support\Facades\MyRedis::class => 'MyRedis',
        App\Tools\Support\Facades\RabbitMQ::class => 'RabbitMQ',
        App\Tools\Support\Facades\PhpRedis::class => 'PhpRedis',
        App\Tools\Support\Facades\Xhprof::class => 'Xhprof'
    ],

    /**
     * ProviderService配置
     */
    'providers' => [
        App\Providers\ConfigServiceProvider::class,
        App\Providers\ArrayUnitServiceProvider::class,
        App\Providers\SeasLogServiceProvider::class,
        App\Providers\FolderServiceProvider::class,
        App\Providers\RedisServiceProvider::class,
        App\Tools\Rabbit\RabbitMQServiceProvider::class,
        App\Tools\Redis\PhpRedisServiceProvider::class,
        App\Providers\XhprofServiceProvider::class
    ]
];