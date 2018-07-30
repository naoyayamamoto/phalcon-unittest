<?php

$config =  include __DIR__ . "/../app/config/config.php";

$config->merge(
    new \Phalcon\Config([
        'database' => [
            'adapter'    => 'Mysql',
            'host'       => 'localhost',
            'username'   => 'root',
            'password'   => '',
            'dbname'     => 'test_api',
            'charset'    => 'utf8mb4'
        ],
        'application' => [
            'rootDir' => __DIR__,
            'mocksDir' => __DIR__ . "/_mocks",
            'fixturesDir' => __DIR__ . "/_fixtures",
            'traitsDir' => __DIR__ . "/_traits"
        ]
    ])
);

return $config;
