<?php

/**
 * Dependencies
 */
$di->setShared('router', function () {
    return new \Phalcon\Mvc\Router;
});

$di->setShared('request', function () {
    return new RequestMock;
});

$di->setShared('response', function () {
    return new \Phalcon\Http\Response;
});

/**
 * Mocks
 */
$di->setShared('cors', function () {
    return new CORSPluginMock;
});

$di->setShared('auth', function () {
    return new AuthPluginMock;
});

/**
 * Global Services
 */
$di->setShared('config', function () {
    return include __DIR__ . "/config.php";
});

$di->set('logger', function () use ($di) {
    $logger = new \Phalcon\Logger\Adapter\File($di->getShared('config')->application->logDir . 'unittest.log');
    $formatter = new \Phalcon\Logger\Formatter\Line("[%date%][%type%] %message%", "Y-m-d H:i:s");
    $logger->setFormatter($formatter);
    return $logger;
});

$di->setShared('db', function () use ($di) {
    $class = 'Phalcon\Db\Adapter\Pdo\Mysql';
    $connection = new $class([
        'host'     => $di->getConfig()->database->host,
        'username' => $di->getConfig()->database->username,
        'password' => $di->getConfig()->database->password,
        'dbname'   => $di->getConfig()->database->dbname,
        'charset'  => $di->getConfig()->database->charset,
        'persistent' => true,
        'options' => [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]
    ]);

    return $connection;
});

$di->setShared('security', function () use ($di) {
    $security = new \Phalcon\Security();
    $security->setWorkFactor(8);
    return $security;
});
