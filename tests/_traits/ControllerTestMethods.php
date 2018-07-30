<?php

/**
 * コントローラーをテストするメソッド群
 */
trait ControllerTestMethods
{
    /**
     * Dispatches the given url and parameter
     *
     * @param  string $url The request url
     * @param  array  $param The request body
     * @param  string $method The request method
     * @return void
     */
    protected function dispatch($url, $param = [], $method = 'GET')
    {
        // urlの先頭に/がなければつける
        if (strpos($url, '/') !== 0) {
            $url = '/' . $url;
        }
        $_GET["_url"] = $url;
        // リクエストパラメーターがあればPOSTにする
        $_SERVER["REQUEST_METHOD"] = (empty($param)) ? $method : 'POST';
        $this->di->getShared('request')->setJsonRawBody($param);
        $this->application->handle();
    }

    /**
     * Asserts that the response code matches the given one
     *
     * @param  string $expected the expected response code
     * @param  string $message error message
     */
    protected function assertResponseCode($expected, $message = '')
    {
        // convert to string if int
        if (is_string($expected)) {
            $expected = (int) $expected;
        }

        $actualValue = $this->di->getShared('response')->getStatusCode();

        // Phalcon version < 3.2.1
        if (is_string($actualValue)) {
            $actualValue = substr($actualValue, 0, 3);
        }

        $this->assertEquals($expected, $actualValue, $message);
    }

    /**
     * Gets the HTTP response body with json_decode
     */
    protected function getJsonContent()
    {
        return json_decode($this->di->getShared('response')->getContent(), true);
    }
}
