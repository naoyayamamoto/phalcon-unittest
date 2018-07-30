<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;

class CORSPluginMock extends Plugin {

    public function beforeHandleRoute(Event $event, Micro $app)
    {
    }
}
