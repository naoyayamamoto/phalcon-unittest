<?php

class SampleController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->response->setStatusCode(200);
        $this->response->setJsonContent([
            "data" => [1, 2, 3]
        ]);
    }

}
