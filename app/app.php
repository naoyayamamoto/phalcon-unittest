<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Events\Manager as EventsManager;

// CORSPlugin適用
$eventsManager = new EventsManager();
$eventsManager->attach('micro:beforeHandleRoute', $di->get('cors'));

$app->setEventsManager($eventsManager);

$mc = new MicroCollection();
$mc->setHandler("SampleController", true);
$mc->setPrefix("/");

$mc->get("/", "indexAction");
$app->mount($mc);

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    return $app->response->setJsonContent(
        ["message" => "Not Found"],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
});

/**
 * Error Handler
 */
$app->error(
    function ($exception) use ($app) {
        $content = [
            'message' => $exception->getMessage()
        ];
        $app->response->setStatusCode($exception->getCode());

        $app->response->setJsonContent($content);
        $app->response->send();
        return false;
    }
);

$app->finish(function() use ($app) {
    $app->response->send();
});
