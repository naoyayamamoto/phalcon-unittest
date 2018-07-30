<?php

use Phalcon\Di;
use Phalcon\Mvc\Micro;
use Phalcon\Test\ModelTestCase as PhalconTestCase;
use Phalcon\Mvc\Model\Migration;
use Phalcon\Db\AdapterInterface;

abstract class UnitTestCase extends PhalconTestCase
{
    use ControllerTestMethods;
    /**
     * @var bool
     */
    private $_loaded = false;

    /**
     * テストで使用するデータを入れるテーブル
     */
    protected $prepareTable = [];

    public function setUp()
    {
        parent::setUp();

        // Load any additional services that might be required during testing
        $di = Di::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent
        include __DIR__ . "/TestServices.php";

        $this->setDi($di);

        $this->_loaded = true;

        // Set Micro Application
        $app = new Micro($this->di);

        // Stop Response
        $app->finish(function () use ($app) {
            $app->stop();
        });
        // Include Application handler
        include __DIR__ . "/../app/app.php";

        // Override Error Handler
        $app->error(function($exception) use ($app) {
            $content = [
                'message' => $exception->getMessage()
            ];
            $app->response->setStatusCode($exception->getCode());

            $app->response->setJsonContent($content);
            return false;
        });

        $this->application = $app;

        /**
         * テスト開始前にトランザクションに入れる
         */
        $this->di->getShared('db')->begin();
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function tearDown()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
        /**
         * テストによって操作されたデータをロールバック
         */
        $this->di->getShared('db')->rollback();
        parent::tearDown();
        $this->di->getShared('db')->close();
        $this->di->reset();
        $this->application = null;
        $this->prepareTable = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION = [];
        $_GET =  [];
        $_POST = [];
        $_COOKIE = [];
        $_REQUEST = [];
        $_FILES = [];
    }
}
