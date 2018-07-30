<?php

/**
 * コントローラーをテストするメソッド群
 */
trait ControllerTestMethods
{
    /**
     * エラー時のメッセージ
     * @var string
     */
    protected $message = '';
    /**
     * エラー時のメッセージの設定
     * @param string $str
     */
    protected function setMessage($str)
    {
        $this->message = $str;
    }
    /**
     * エラー時のメッセージをクリア
     */
    protected function clearMessage()
    {
        $this->message = '';
    }
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

    /**
     * データ検証をする際にデフォルトで無視するパラムを取得する
     * @return string[]
     */
    protected function getDefaultFilter()
    {
        return ['creator', 'updater', 'create_at', 'update_at', 'deleted'];
    }

    /**
     * Assert Response code & body
     * @param integer $expected_code
     * @param mixed $expected_data
     * @param boolean|array $fillter
     */
    protected function assertResponse($expected_code, $expected_data = '', $filter = false)
    {
        $this->assertResponseCode($expected_code, $this->message);
        $data = $this->getJsonContent()['data'];
        if ($filter) {
            $filter_param = is_array($filter) ? $filter : $this->getDefaultFilter();
            // レスポンスデータをフィルター
            $data = $this->walk_recursive_remove($data, function ($value, $key) use ($filter_param) {
                return in_array($key, $filter_param);
            });
            // 予想データもフィルター
            $expected_data = $this->walk_recursive_remove($expected_data, function ($value, $key) use ($filter_param) {
                return in_array($key, $filter_param);
            });
        }
        $this->assertEquals($expected_data, $data, $this->message);
        $this->clearMessage();
    }

    /**
     * 再帰的に指定パラムの削除を行う
     * @param  array    $array
     * @param  callable $callback
     * @return array
     */
    private function walk_recursive_remove(array $array, callable $callback)
    {
        foreach ($array as $k => $v) {
            if ($callback($v, $k)) {
                unset($array[$k]);
                continue;
            }

            if (is_array($v)) {
                $array[$k] = $this->walk_recursive_remove($v, $callback);
            }
        }

        return $array;
    }

    /**
     * 検証用パラメータ作成
     * @param  mixed $param
     * @param  string $method
     * @return array
     */
    protected function make($param, $method = 'POST')
    {
        return [
            'method' => $method,
            'param' => $param
        ];
    }
}
