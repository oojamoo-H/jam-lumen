<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix' => env('DB_PREFIX', ''),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'timezone' => env('DB_TIMEZONE', '+00:00'),
            'strict' => env('DB_STRICT_MODE', FALSE),
        ],

//        'mysql_old' => [
//            'driver'    => 'mysql',
//            'host'      => env('DB_HOST', 'localhost'),
//            'port'      => env('DB_PORT', 3306),
//            'database'  => env('DB_DATABASE_OLD', 'RuiguCRM_Dev'),
//            'username'  => env('DB_USERNAME', 'forge'),
//            'password'  => env('DB_PASSWORD', ''),
//            'charset'   => env('DB_CHARSET', 'utf8mb4'),
//            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
//            'prefix'    => env('DB_PREFIX', ''),
//            'timezone'  => env('DB_TIMEZONE_OLD', '+00:00'),
//            'strict'    => env('DB_STRICT_MODE_OLD', false),
//        ],

        'mysql_old' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_OLD', 'localhost'),
            'port' => env('DB_PORT_OLD', 3306),
            'database' => env('DB_DATABASE_OLD', 'forge'),
            'username' => env('DB_USERNAME_OLD', 'forge'),
            'password' => env('DB_PASSWORD_OLD', ''),
            'charset' => env('DB_CHARSET_OLD', 'utf8mb4'),
            'collation' => env('DB_COLLATION_OLD', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX_OLD', ''),
            'timezone' => env('DB_TIMEZONE_OLD', '+00:00'),
            'strict' => env('DB_STRICT_MODE_OLD', FALSE),
        ],


        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => env('DB_PREFIX', ''),
            'schema' => env('DB_SCHEMA', 'public'),
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => env('DB_PREFIX', ''),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => env('REDIS_CLUSTER', FALSE),
        'client' => env('REDIS_CLIENT', 'phpredis'),

        'default' => [
            'host' => env('REDIS_HOST_M', '127.0.0.1'),
            'port' => env('REDIS_PORT_M', '6379'),
            'database' => env('REDIS_DATABASE_M', 0),
            'password' => env('REDIS_PASSWORD_M', NULL),
            'persistent' => env('REDIS_PERSISTENT', FALSE),
        ],

        'clusters' => [
            'default' => [
                'server1' => [
                    'host' => env('REDIS_HOST_M', '127.0.0.1'),
                    'port' => env('REDIS_PORT_M', '6379'),
                    'database' => env('REDIS_DATABASE_M', 0),
                    'password' => env('REDIS_PASSWORD_M', NULL),
                ],

                'server2' => [
                    'host' => env('REDIS_HOST_S1', '127.0.0.1'),
                    'port' => env('REDIS_PORT_S1', '6379'),
                    'database' => env('REDIS_DATABASE_S1', 0),
                    'password' => env('REDIS_PASSWORD_S1', NULL),
                ],

                'server3' => [
                    'host' => env('REDIS_HOST_S2', '127.0.0.1'),
                    'port' => env('REDIS_PORT_S2', '6379'),
                    'database' => env('REDIS_DATABASE_S2', 0),
                    'password' => env('REDIS_PASSWORD_S2', NULL),
                ]
            ]
        ],
    ]
];
