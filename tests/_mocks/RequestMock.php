<?php

/**
 * \Phalcon\Http\Requestのテスト用サービス
 */
class RequestMock extends \Phalcon\Http\Request
{
    public function setJsonRawBody($content)
    {
        $this->_rawBody = json_encode($content);
    }
}
