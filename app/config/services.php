<?php

use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Logger;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
use Phalcon\Mvc\Model\Manager as ModelsManager;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($di) {


    $config = $this->getConfig();
    $schema = $di->get('my')->getSchema();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $connection = new $class([
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => !empty($schema) ? $schema : $config->database->dbname,
        'charset'  => $config->database->charset,
        'options' => [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]
    ]);

    $eventsManager = new EventsManager();

    $logger = new FileLogger(APP_PATH . "/logs/debug.log");

    // Listen all the database events
    $eventsManager->attach(
        "db:beforeQuery",
        function ($event, $connection) use ($logger) {
            $logger->log(
                $connection->getSQLStatement(),
                Logger::DEBUG
            );
        }
    );

    if ( MODE == MODE_DEBUG ) {
        $connection->setEventsManager($eventsManager);
    }

    return $connection;
});

$di->set('cors', function() {
    return new CORSPlugin;
}, true);

// ロガー関連
$di->set('logger', function () use ($di) {
    $logger = new FileAdapter($di->getConfig()->application->logDir.'error.log');
    $formatter = new LineFormatter("[%date%][%type%] %message%", "Y-m-d H:i:s");
    $logger->setFormatter($formatter);
    return $logger;
});
